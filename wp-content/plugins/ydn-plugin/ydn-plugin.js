(function($) {
  var YDN = window.YDN || (window.YDN = {});
  var $body = $('body');

  function initialize() {
    ydn_xc_widget_init();
  };


  function ydn_xc_widget_init() {
    var $widget = $('.ydn-plugin.widget#cross-campus .content-list');
    var $widget_posts = $('#ydn-xc-widget-posts');
    if ($widget.length == 0 || $widget_posts.length == 0) { return; }

    //disable the no-js flag and grab the full content from the footer
    $widget.removeClass('no-js').html( $widget_posts.html() );
  }

  $(document).ready(initialize);
 
} (jQuery));
