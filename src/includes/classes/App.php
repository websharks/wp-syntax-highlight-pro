<?php
/**
 * Application.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
namespace WebSharks\WpSharks\WpSyntaxHighlight\Pro\Classes;

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
 * Application.
 *
 * @since 160829.41802 Initial release.
 */
class App extends SCoreClasses\App
{
    /**
     * Version.
     *
     * @since 160829.41802
     *
     * @type string Version.
     */
    const VERSION = '170326.47117'; //v//

    /**
     * Constructor.
     *
     * @since 160829.41802 Initial release.
     *
     * @param array $instance Instance args.
     */
    public function __construct(array $instance = [])
    {
        $instance_base = [
            '©di' => [
                '©default_rule' => [
                    'new_instances' => [
                    ],
                ],
            ],

            '§specs' => [
                '§in_wp'           => false,
                '§is_network_wide' => false,

                '§type' => 'plugin',
                '§file' => dirname(__FILE__, 4).'/plugin.php',
            ],
            '©brand' => [
                '©acronym' => 'WP SYNTAX HL',
                '©name'    => 'WP Syntax Highlight',

                '©slug' => 'wp-syntax-highlight',
                '©var'  => 'wp_syntax_highlight',

                '©short_slug' => 'wp-syn-hl',
                '©short_var'  => 'wp_syn_hl',

                '©text_domain' => 'wp-syntax-highlight',
            ],

            'hljs' => [
                'cdn_files_list_url' => 'https://cdnjs.com/libraries/highlight.js',
                'style_demos_url'    => 'https://highlightjs.org/static/demo/',
            ],
            '§pro_option_keys' => [],

            '§default_options' => [
                'lazy_load' => true,

                'hljs_style'       => 'github',
                'hljs_bg_color'    => '', // Defaults to style definition.
                'hljs_font_family' => "'Hack', 'Menlo', 'Monaco', 'Consolas', 'Andale Mono', 'DejaVu Sans Mono', monospace",

                'hljs_selectors'  => 'pre > code',
                'hljs_exclusions' => '.no-hljs, .no-highlight, .nohighlight',
                'hljs_plain_text' => '.lang-none, .lang-plain, .lang-text, .lang-txt, .none, .plain, .text, .txt',
            ],

            '§conflicts' => [
                '§plugins' => [
                    'wp-syntax' => 'WP Syntax',
                ],
            ],
        ];
        parent::__construct($instance_base, $instance);
    }

    /**
     * Early hook setup handler.
     *
     * @since 160829.41802 Initial release.
     */
    protected function onSetupEarlyHooks()
    {
        parent::onSetupEarlyHooks();
    }

    /**
     * Other hook setup handler.
     *
     * @since 160829.41802 Initial release.
     */
    protected function onSetupOtherHooks()
    {
        parent::onSetupOtherHooks();

        add_action('admin_menu', [$this->Utils->MenuPage, 'onAdminMenu']);
        add_filter('body_class', [$this->Utils->StylesScripts, 'onBodyClass']);
        add_action('wp_enqueue_scripts', [$this->Utils->StylesScripts, 'onWpEnqueueScripts']);
    }
}
