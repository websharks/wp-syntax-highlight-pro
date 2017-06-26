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
        $brand_slug = $this->App->Config->©brand['©slug'];
        $brand_var  = $this->App->Config->©brand['©var'];

        s::enqueueHighlightJsLibs($settings['hljsStyle'], $settings['hljsLangs']);

        s::enqueueLibs(__METHOD__, [
            'styles' => [
                $brand_slug => [
                    'deps' => ['highlight-js'],
                    'ver'  => $this->App::VERSION,
                    'url'  => c::appUrl('/client-s/css/site/style.min.css'),
                ],
            ],
            'scripts' => [
                $brand_slug => [
                    'ver'      => $this->App::VERSION,
                    'deps'     => ['jquery', 'highlight-js'],
                    'url'      => c::appUrl('/client-s/js/site/script.min.js'),
                    'localize' => [
                        'key'  => c::varToCamelCase($brand_var).'Data',
                        'data' => [
                            'brand' => [
                                'slug' => $brand_slug,
                                'var'  => $brand_var,
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
        $is_applicable  = null; // Initialize.
        $lazy_load_says = null; // Initialize.

        $lazy_load        = s::getOption('lazy_load');
        $lazy_load_marker = '<!--'.$this->App->Config->©brand['©slug'].'-->';

        $hljs_style = s::getOption('hljs_style');
        $hljs_langs = s::getOption('hljs_langs');
        $hljs_langs = preg_split('/[,\s]+/u', $hljs_langs, -1, PREG_SPLIT_NO_EMPTY);

        $hljs_bg_color    = s::getOption('hljs_bg_color');
        $hljs_font_family = s::getOption('hljs_font_family');

        $hljs_selectors  = s::getOption('hljs_selectors');
        $hljs_plain_text = s::getOption('hljs_plain_text');
        $hljs_exclusions = s::getOption('hljs_exclusions');

        if (!$hljs_selectors) { // Must have selectors.
            return $settings = []; // Not possible.
        }
        if (!isset($is_applicable) && $lazy_load) {
            if (!is_singular()) {
                $lazy_load_says = false;
            } elseif (!($WP_Post = get_post())) {
                $lazy_load_says = false;
            } elseif (mb_stripos($WP_Post->post_content, $lazy_load_marker) !== false) {
                $lazy_load_says = true; // Explicitly enabled by comment marker.
            } elseif (($hljs_selectors === 'pre > code' || $hljs_selectors === 'pre.code > code')
                && (mb_stripos($WP_Post->post_content, '<pre') === false || mb_stripos($WP_Post->post_content, '<code') === false)
                && mb_stripos($WP_Post->post_content, '[snippet') === false // `[snippet]` shortcode.
                && mb_stripos($WP_Post->post_content, '[md') === false // `[md]` shortcode.
            ) {
                $lazy_load_says = false; // Nothing to highlight in this case.
            }
        } // Give filters a chance to override detections above.
        $is_applicable = s::applyFilters('is_applicable', $is_applicable, $lazy_load_says);

        if ($is_applicable === false
            || (!isset($is_applicable) && $lazy_load_says === false)
        ) {
            return $settings = []; // Not applicable.
        }
        return $settings = s::applyFilters('script_settings', [
            'hljsStyle'      => $hljs_style,
            'hljsLangs'      => $hljs_langs,

            'hljsBgColor'    => $hljs_bg_color,
            'hljsFontFamily' => $hljs_font_family,

            'hljsSelectors'  => $hljs_selectors,
            'hljsPlainText'  => $hljs_plain_text,
            'hljsExclusions' => $hljs_exclusions,
        ]);
    }
}
