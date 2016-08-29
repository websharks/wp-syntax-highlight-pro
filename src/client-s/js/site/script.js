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
        $parent.addClass('code'); // i.e., `<pre class="code"><code>...`
      } else if ($this.prop('tagName') === 'code' && $parent.prop('tagName') === 'pre') {
        $parent.addClass('code'); // Same; but only for this specific tab combo.
      }
      if (x.settings.hljsBgColor) {
        $this.css('background', x.settings.hljsBgColor);
      }
      if (x.settings.hljsFontFamily) {
        $this.css('font-family', x.settings.hljsFontFamily);
      }
      hljs.highlightBlock(this); // Highlight.js.
    });
  });
})(jQuery);
