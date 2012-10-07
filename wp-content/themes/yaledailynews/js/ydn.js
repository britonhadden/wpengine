(function($) {

  var YDN = window.YDN || (window.YDN = {});

  function initialize() {
    equally_space_horizontally('#menu-primary-menu'); //spaces the links in the nav under the masthead

    var $body = $('body');
    //run the scripts for a single-post
    if ( $body.hasClass('single-post') ) {
      attach_social_handlers();
    }

   if ( $body.hasClass('home') ) {
     homepage_carousel_init();
   }

   if ($('#weekend').length > 0) {
     weekend_top_nav();
   }

   if ($body.hasClass('category-opinion') ) {
     opinion_init();
   }
  };

  /* social share buttons on story pages should launch popups
   * that are centered on the page and that provide appropriate
   * data about the object */
  function attach_social_handlers() {
    var $social_share = $('.social-share');
  
    /* generate the share parameters on page load, use them when the handlers fire */
    var fb_object = { 'method': 'feed',
                      'link': extract_metadata_for_key('url'),
                      'picture': extract_metadata_for_key('image'),
                      'name': extract_metadata_for_key('title'),
                      'description': extract_metadata_for_key('description') };

    var twitter_object = { 'text': 'Checkout "'+ extract_metadata_for_key('title') + '"! ' + extract_metadata_for_key('url'),
                           'related': 'yaledailynews' };
                      
    /* bind the event handlers */
    $social_share.find('.facebook').click( function() {
       FB.ui( fb_object );
       return false;
    } );


    $social_share.find('.twitter').click( function() {
      var D=550,A=450,C=screen.height,B=screen.width,H=Math.round((B/2)-(D/2)),G=0,F=document,E;if(C>A){G=Math.round((C/2)-(A/2))}
      window.open('http://twitter.com/share?' + $.param(twitter_object),'','left='+H+',top='+G+',width='+D+',height='+A+',personalbar=0,toolbar=0,scrollbars=1,resizable=1');
      return false;
    } );

  };

  /* extract metadata from the DOM for use in JS */
  function extract_metadata_for_key(key) {
    var $el = $('meta[property$=' + key + ']');
    if ( $el !== undefined && $el.length > 0 ) {
      $el = $el[0];
      return $el.content;
    } else {
      return '';
   }
  };

  /**
   * replace the no-javascript HTML markup with the javascript-enabled markup that gets rendered
   * into a <script> tag on the bottom of the page. then initialize the rotation.
   */
  function homepage_carousel_init() {
    var $home_carousel = $('#home-carousel');
    var $home_carousel_template = $('#home-carousel-template');
    var $navlist, nav_height, $items, sliding = false;

    $home_carousel.removeClass('no-js').html( $home_carousel_template.html() );
    $home_carousel.carousel({ interval: 7000, pause: false });

    $navlist = $home_carousel.find('.navlist'); 
    nav_height = $navlist.height();

    $items = $home_carousel.find('.item');
    /* this loop is pretty messy, but it's doing the job.
     * it 1) makes sure the captions for each picture are tall enough to hold the nav list
     *    2) binds the mouse over events for the navigation */
   
    $items.addClass('force-display'); //the items need to be visible so that the height calculations will work
    $items.find('.carousel-caption').each(function(item_index, item_obj) {
      var $item_obj = $(item_obj);

      if (nav_height > $item_obj.height() ) {
        $item_obj.height(nav_height);
      }

      $item_obj.find('.navlist li').each(function(li_index, li_obj) {
        $li_obj = $(li_obj);
        if (!$li_obj.hasClass('arrow')) {
          $li_obj.mouseenter( function() { 
            if (! sliding ) {
              $home_carousel.removeClass('slide').carousel(li_index).addClass('slide').carousel('pause');
              return false;
            }
          } );
        }
      });
    });
    $items.removeClass('force-display'); //allow the carousel styling to take over again


    /* keep the sliding variable updated so that event handlers disable when sliding is happening */
    $home_carousel.bind('slide', function() { sliding = true; } );
    $home_carousel.bind('slid', function() { sliding = false; } );

    /* there were some problems with bootstrap's implementation of pause-on-hover. When multiple 
     * carousel(index)'s were calle in a short time span, the cycling didn't get reset appropriately.
     * adding these handlers fixed the issues. */
    $home_carousel.mouseenter( function() { $home_carousel.carousel('pause'); } );
    $home_carousel.mouseleave( function() { $home_carousel.carousel('cycle'); } );

  };

  /* sets up scrolling on the weekend top nav. if the user scrolls past the navigation,
   * it will have it's position fixed to the top of the browser */
  function weekend_top_nav() {
    var header_fixed, $window, $header, header_offset;

    //set the defaults for the values
    //cache jQuery slectors so the event handler wont keep
    //looking them up
    header_fixed = false;
    $window = $(window);
    $header = $('#weekend .header .wrapper');
    header_offset = $header.offset().top;

    //define the event handler
    function scroll_handler() {
      var scroll_top = $window.scrollTop();
      if (header_fixed && scroll_top < header_offset) {
        $header.css('position','relative');
        header_fixed = false;
      } else if ( !header_fixed && scroll_top > header_offset) {
        $header.css('position','fixed');
        header_fixed = true;
      }
    };

    //wrap the event handler in a throttle function to prevent excessive calls
    //then attach it
    $window.scroll( $.throttle(100,scroll_handler) );
  };

  /* some initialization for the opinion category landing page */
  function opinion_init() {
    equally_space_horizontally('#opinion-cat-selector');
  };

  /* utility function used to evenly space <li> elements horizontally */
  /* selector is a CSS selector that targets the item you want to affect */
  /* the selector should be an ID to guarantee uniqueness */
  function equally_space_horizontally(selector)  {
    var $selector = $(selector); //this is the UL we're styling
    if ($selector.length == 0) { return; }

    $selector.removeClass('no-script'); //no-script styles can be applied to manage spacing very roughly
    var container_width = $selector.width();
    var links_width = 0;
    $selector.children().each(function() {
      links_width += $(this).outerWidth(true);
    });
    var link_spacing = Math.floor((container_width - links_width) / ($selector.children().length - 1)) - 1;
    $selector.find('> li:not(:last-child)').css('margin-right', link_spacing + "px");
  };

  $(document).ready( initialize );

} (jQuery) );
