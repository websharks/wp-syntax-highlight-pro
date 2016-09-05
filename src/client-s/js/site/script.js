(function ($) {
  $(document).ready(function () {
    /*
     * Plugin-specific data.
     */
    var x = gnyQWfVLQSyCrXargTDZJRxKStxKAHMcData;

    /*
     * Syntax highlighting via Highlight.js.
     */
    $(x.settings.hljsSelectors).not(x.settings.hljsExclusions).each(function () {
      var $this = $(this),
        $parent = $this.parent();

      if (x.settings.hljsSelectors === 'pre > code') {
        $parent.addClass('hljs-pre'); // i.e., `<pre class="hljs-pre"><code>...`
      } else if ($this.prop('tagName') === 'code' && $parent.prop('tagName') === 'pre') {
        $parent.addClass('hljs-pre'); // Same; but only for this specific tab combo.
      }
      if (x.settings.hljsBgColor) {
        $this.css('background', x.settings.hljsBgColor);
      }
      if (x.settings.hljsFontFamily) {
        $this.css('font-family', x.settings.hljsFontFamily);
      }
      if ($this.is(x.settings.hljsPlainText)) {
        $this.addClass('hljs'); // Treat as plain text.
      } else {
        hljs.highlightBlock(this); // Highlight.js.
      }
    });
  });
})(jQuery);
