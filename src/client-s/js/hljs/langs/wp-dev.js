/*
Language: WP
Requires: xml.js
Author: @jaswsinc
Website: https://jaswsinc.com
Description: Highlights WordPress shortcodes.
Category: common, markup
*/

/*
 * With long/standard keys.
 * See: <http://jas.xyz/2bLJcaG>
 */
hljs.registerLanguage('wp', function (hljs) {
  return {
    aliases: [
      'wpsc',
      'wordpress',
      'wp-shortcode',
      'wp-shortcodes',
      'wordpress-shortcode',
      'wordpress-shortcodes'
    ],
    contains: [{
      className: 'tag',
      begin: /\[\/?/,
      end: /\/?\]/,
      relevance: 10,

      contains: [{
        className: 'name',
        begin: /[a-z_\-][a-z0-9_\-]*/i,
        relevance: 0
      }, {
        illegal: /[[\]]/,
        endsWithParent: true,
        relevance: 0,

        contains: [{
          className: 'attr',
          begin: /[a-z0-9_\-]+/i,
          relevance: 0
        }, {
          begin: /\=\s*/,
          relevance: 0,

          contains: [{
            className: 'string',
            endsParent: true,

            variants: [{
              begin: /"/,
              end: /"/
            }, {
              begin: /'/,
              end: /'/
            }, {
              begin: /[^\="'\s[\]]+/
            }]
          }]
        }]
      }]
    }, {
      begin: /</,
      end: />/,
      subLanguage: 'xml',
      relevance: 0
    }]
  };
});
