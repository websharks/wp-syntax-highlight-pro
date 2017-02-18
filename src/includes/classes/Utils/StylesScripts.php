<?php
/**
 * Styles/scripts.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
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
 * @since 160829.41802 Initial release.
 */
class StylesScripts extends SCoreClasses\SCore\Base\Core
{
    /**
     * Is applicable?
     *
     * @since 160829.41802 Initial release.
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
     * @since 160829.41802 Initial release.
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
     * @since 160829.41802 Initial release.
     */
    public function onWpEnqueueScripts()
    {
        if (!$this->isApplicable()) {
            return; // Not applicable.
        } elseif (!($settings = $this->applicableSettings())) {
            return; // Not applicable.
        }
        s::enqueueHighlightJsLibs($settings['hljsStyle']);

        s::enqueueLibs(__METHOD__, [
            'styles' => [
                $this->App->Config->©brand['©slug'] => [
                    'ver'  => $this->App::VERSION,
                    'deps' => ['highlight-js'],
                    'url'  => c::appUrl('/client-s/css/site/style.min.css'),
                ],
            ],
            'scripts' => [
                $this->App->Config->©brand['©slug'] => [
                    'ver'      => $this->App::VERSION,
                    'deps'     => ['jquery', 'highlight-js', 'highlight-js-lang-wp'],
                    'url'      => c::appUrl('/client-s/js/site/script.min.js'),
                    'localize' => [
                        'key'  => 'kkagfv2gdyd7wu2ambarnb2n6vcpbr83Data',
                        'data' => [
                            'brand' => [
                                'slug' => $this->App->Config->©brand['©slug'],
                                'var'  => $this->App->Config->©brand['©var'],
                            ],
                            'settings' => $settings, // Filterable.
                            // See filter below in `applicableSettings()`.
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Applicable settings.
     *
     * @since 160829.41802 Initial release.
     *
     * @return array Settings if applicable.
     */
    protected function applicableSettings(): array
    {
        if (($settings = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $settings; // Cached this already.
        }
        $is_applicable_filter = s::applyFilters('is_applicable', null);

        if ($is_applicable_filter === false) {
            return $settings = []; // Not applicable.
        }
        $hljs_style       = s::getOption('hljs_style');
        $hljs_bg_color    = s::getOption('hljs_bg_color');
        $hljs_font_family = s::getOption('hljs_font_family');

        $hljs_selectors  = s::getOption('hljs_selectors');
        $hljs_plain_text = s::getOption('hljs_plain_text');
        $hljs_exclusions = s::getOption('hljs_exclusions');

        if (!$hljs_selectors) { // Must have selectors.
            return $settings = []; // Not possible.
        }
        return $settings = s::applyFilters('script_settings', [
            'hljsStyle'      => $hljs_style,
            'hljsBgColor'    => $hljs_bg_color,
            'hljsFontFamily' => $hljs_font_family,

            'hljsSelectors'  => $hljs_selectors,
            'hljsPlainText'  => $hljs_plain_text,
            'hljsExclusions' => $hljs_exclusions,
        ]);
    }
}
