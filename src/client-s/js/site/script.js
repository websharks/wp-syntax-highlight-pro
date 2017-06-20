(function ($) {
  $(document).ready(function () {
    /*
     * Plugin-specific data.
     */
    var x = wpSyntaxHighlightData;

    /*
     * Escape selectors.
     * Allows for special chars in class names.
     */
    var escSelectors = function (s) {
      return s.replace(/\.([^\s]+)/g, function (m0, m1) {
        return '.' + m1.replace(/([!@#~+])/g, '\\$1');
      });
    };

    /*
     * Highlight.js.
     * Based on configuration.
     */
    $(escSelectors(x.settings.hljsSelectors))
      .not(escSelectors(x.settings.hljsExclusions))
      .each(function () {
        var $this = $(this),
          $parent = $this.parent();

        if (x.settings.hljsSelectors === 'pre > code') {
          $parent.addClass('hljs-pre code');
        } else if (x.settings.hljsSelectors === 'pre.code > code') {
          $parent.addClass('hljs-pre code');
        } else if ($this.prop('tagName') === 'code' && $parent.prop('tagName') === 'pre') {
          $parent.addClass('hljs-pre code');
        }
        if (x.settings.hljsBgColor) {
          $this.css('background', x.settings.hljsBgColor);
        }
        if (x.settings.hljsFontFamily) {
          $this.css('font-family', x.settings.hljsFontFamily);
        }
        if ($this.is(x.settings.hljsPlainText)) {
          $this.addClass('hljs lang-none');
        } else {
          hljs.highlightBlock(this);
        }
      });
  });
})(jQuery);
