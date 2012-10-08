(function($) {
  
  function init_onready() {
    build_sidebar_tabs();
  };

  function init_onload()  {
    //wait for the images to load before adjusting columns,
    //in case they change the height of primary
    adjust_column_heights();
  }; 


  function adjust_column_heights() {
    /* when the sidebar column is taller than the content area, the featured story panel floats awkwardly in the
     * middle of the page. This function applies a class that triggers absolute positioning on the featured
     * story footer */
    var $secondary = $("#secondary"), $primary = $("#primary");
    if ( $secondary.length == 1 && $primary.length == 1 && $secondary.height() > $primary.height() ) {
      $("#nav-below").addClass("position-bottom");
    }
  }

  function build_sidebar_tabs() {
    /* ideally this would get rendered server side, but WP doesn't have a simple method for creating multiple render
     * formats for a widget.  Thus we're building the tab navigation on the client side ( just extracting the text from the headline elements and then using the WP
     * markup as our tab body) and then enabling the tabbed interface */
    var $tabs = $('.tab');
    var $tabs_navigation;
    var $tabbed = $('#tabbed').addClass('js'); //activate styles by marking JS on
    if ( $tabs.length == 0 ) {
      return;
    }

    $tabs_navigation = $( document.createElement('ul') ).addClass('nav nav-tabs');
    $tabs.each( function(index,tab) {
      var $tab = $(tab);
      var $tab_li, $tab_a, $tab_title, title_text, title_id;

      //Extract the useful info from the title, then remove it
      $tab_title = $tab.find('.widget-title');
      if ($tab_title.children().length !== 0) {
        //if we haven't hit a text node, try its first child.
        //this fix was originally intended for the twitter widget
        $tab_title = $tab_title.children().first();
      }
      title_text = $tab_title.html();
      tab_id = title_text.replace(' ','').toLowerCase();
      $tab_title.remove();

      //generate the LI element for the nav 
      $tab_li = $( document.createElement('li') );
      $tab_a = $( document.createElement('a') ).attr("href","#" + tab_id).html(title_text);
      $tab_li.append($tab_a);
      $tabs_navigation.append($tab_li); 

      //set the ID on the tab to match the anchor & add the appropriate classes
      $tab.attr("id",tab_id).addClass('tab-pane');
      if (index == 0) {
         $tab_li.addClass('active');
         $tab.addClass('active');
      }

      //bind the tab events to the anchor
      $tab_a.click(function(e) {
        e.preventDefault();
        $(this).tab('show');
      });
   } );
    
   $tabbed.prepend($tabs_navigation);
    
  };

  $(document).ready( init_onready ); 
  $(window).load( init_onload );

} (jQuery) );
