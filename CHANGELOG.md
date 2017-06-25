## $v

- Enhancing default options.
- Enhancing default structural styles.
- Adding Hash CSS framework compatibility.
- Adding support for additional custom Highlight.js languages.

## v170329.47664

- Adding lazy load configuration option.
- Adding `.none` to list of ignored classes.
- Now pulling base structural styles from WS core lib.
- Updating minimum required version of the WP Sharks Core.
- Updating scripts/styles to bring them inline with core standards.
- Adding `integrity=""` attributes to Highlight.js style/script tags.
- Enhancing security by removing `basename(__FILE__)` from direct access notices.
- Removing unnecessary lite build variation.

## v160919.19768

- Bug fix. The `wp` language should be caSe-insensitive.
- Bug fix. Should be `.plain, .text` (w/ comma) in the default config option value for exclusions.
- Bug fix. Removing `inherit` styles in favor of Highlight.js style selection or configuration option.

## v160829.42099

- Initial release.
- Adding special `wp` language in support of shortcode syntax highlighting.
- Many configurable options. See: **Dashboard → Settings WP Sytax Highlight**
