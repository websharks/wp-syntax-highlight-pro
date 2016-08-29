<?php
/**
 * Styles/scripts.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare (strict_types = 1);
namespace WebSharks\WpSharks\WpSyntaxHighlight\Pro\Classes\Utils;

use WebSharks\WpSharks\WpSyntaxHighlight\Pro\Classes;
use WebSharks\WpSharks\WpSyntaxHighlight\Pro\Interfaces;
use WebSharks\WpSharks\WpSyntaxHighlight\Pro\Traits;
#
use WebSharks\WpSharks\WpSyntaxHighlight\Pro\Classes\AppFacades as a;
use WebSharks\WpSharks\WpSyntaxHighlight\Pro\Classes\SCoreFacades as s;
use WebSharks\WpSharks\WpSyntaxHighlight\Pro\Classes\CoreFacades as c;
#
use WebSharks\WpSharks\Core\Classes as SCoreClasses;
use WebSharks\WpSharks\Core\Interfaces as SCoreInterfaces;
use WebSharks\WpSharks\Core\Traits as SCoreTraits;
#
use WebSharks\Core\WpSharksCore\Classes as CoreClasses;
use WebSharks\Core\WpSharksCore\Classes\Core\Base\Exception;
use WebSharks\Core\WpSharksCore\Interfaces as CoreInterfaces;
use WebSharks\Core\WpSharksCore\Traits as CoreTraits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Styles/scripts.
 *
 * @since $v Initial release.
 */
class StylesScripts extends SCoreClasses\SCore\Base\Core
{
    /**
     * Is applicable?
     *
     * @since $v Initial release.
     *
     * @return bool True if applicable.
     */
    public function isApplicable(): bool
    {
        return (bool) $this->applicableSettings();
    }

    /**
     * On body classes.
     *
     * @since $v Initial release.
     *
     * @param array $classes Body classes.
     *
     * @return array Filtered body classes.
     */
    public function onBodyClass(array $classes): array
    {
        if (!$this->isApplicable()) {
            return $classes; // Not applicable.
        }
        $classes[]      = $this->App->Config->©brand['©slug'];
        return $classes = array_unique($classes);
    }

    /**
     * Handle styles/scripts.
     *
     * @since $v Initial release.
     */
    public function onWpEnqueueScripts()
    {
        if (!$this->isApplicable()) {
            return; // Not applicable.
        } elseif (!($settings = $this->applicableSettings())) {
            return; // Not applicable.
        }
        wp_enqueue_style('highlight-js', '//cdnjs.cloudflare.com/ajax/libs/highlight.js/'.urlencode($this->App->Config->hljs['version']).'/styles/'.urlencode($settings['hljsStyle']).'.min.css', [], null);
        wp_enqueue_style($this->App->Config->©brand['©slug'], c::appUrl('/client-s/css/site/style.min.css'), ['highlight-js'], $this->App::VERSION);

        wp_enqueue_script('highlight-js', '//cdnjs.cloudflare.com/ajax/libs/highlight.js/'.urlencode($this->App->Config->hljs['version']).'/highlight.min.js', [], null, true);
        wp_enqueue_script('highlight-js-lang-wp', c::appUrl('/client-s/js/hljs/langs/wp.min.js'), ['highlight-js'], $this->App::VERSION, true);

        wp_enqueue_script($this->App->Config->©brand['©slug'], c::appUrl('/client-s/js/site/script.min.js'), ['jquery', 'highlight-js', 'highlight-js-lang-wp'], $this->App::VERSION, true);
        wp_localize_script($this->App->Config->©brand['©slug'], 'gnyQWfVLQSyCrXargTDZJRxKStxKAHMcData', [
            'brand' => [
                'slug' => $this->App->Config->©brand['©slug'],
                'var'  => $this->App->Config->©brand['©var'],
            ],
            'settings' => $settings, // Via `applicableSettings()`.
        ]);
    }

    /**
     * Applicable settings.
     *
     * @since $v Initial release.
     *
     * @return array Settings if applicable.
     */
    protected function applicableSettings(): array
    {
        if (($settings = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $settings; // Cached this already.
        }
        $is_applicable_filter = s::applyFilters('is_applicable', null);
        // NOTE: This can be used to force a `true` or `false` value.

        if ($is_applicable_filter === false) {
            return $settings = []; // Not applicable.
        }
        $hljs_style       = s::getOption('hljs_style');
        $hljs_bg_color    = s::getOption('hljs_bg_color');
        $hljs_font_family = s::getOption('hljs_font_family');

        $hljs_selectors  = s::getOption('hljs_selectors');
        $hljs_exclusions = s::getOption('hljs_exclusions');

        if (!$hljs_style || !$hljs_selectors) {
            return $settings = []; // Not applicable.
        }
        return $settings = s::applyFilters('script_settings', [
            'hljsStyle'      => $hljs_style,
            'hljsBgColor'    => $hljs_bg_color,
            'hljsFontFamily' => $hljs_font_family,

            'hljsSelectors'  => $hljs_selectors,
            'hljsExclusions' => $hljs_exclusions,
        ]);
    }
}
