(function($) {
  var YDN = window.YDN || (window.YDN = {});
  var $body = $('body');

  function initialize() {
    ydn_xc_widget_init();
  };


  function ydn_xc_widget_init() {
    var $widget = $('.ydn-plugin.widget#cross-campus');
    var $widget_posts = $('#ydn-xc-widget-posts');
    if ($widget.length == 0 || $widget_posts.length == 0) { return; }

    //disable the no-js flag and grab the full content from the footer
    $widget.removeClass('no-js')
    $widget.find('.content-list').replaceWith($widget_posts.html());

    //fix the height of the container so that there are no wobbling effects
    //effects due to resizing
    var max_height = 0;
    var $items = $widget.find('.item');
    $items.each(function(index, item) {
      var $item = $(item);
      var item_height = $item.outerHeight(true); //inc margin
      max_height = (max_height > item_height) ? max_height : item_height;
      if (index != 0) {
        //unless it's the first one that we're showing, hide it
        $item.hide();
      }
    });
    $widget.find('.content-list').height(max_height); //need to find this again because
                                                      //the DOM node was changed earlier

    //bind the next/prev event handlers
    var max_index = $items.length - 1;
    var current_index = 0;
    function change_panel(direction) {
      //direction is +- 1: 1 indictates next, -1 indicates previous
      $($items[current_index]).hide();
      //the mod ensures that we wrap around on the top end, the less-than check ensures
      //we wrap around on the low end
      current_index = (current_index + direction) % max_index;
      current_index = (current_index > 0) ? current_index : max_index;
      $($items[current_index]).show();
    }

    $widget.find('.next').click(function() {
      change_panel(1);
      return false;
    });

    $widget.find('.prev').click(function() {
      change_panel(-1);
      return false;
    });

  }

  $(document).ready(initialize);
 
} (jQuery));
