(function($) {
  var YDN = window.YDN || (window.YDN = {});

  function initialize() {
    console.log('hi2');

    var $body = $('body');
    //run the scripts for a single-post
    if ( $body.hasClass('single-post') ) {
      attach_social_handlers();
    }

   if ( $body.hasClass('home') ) {
     homepage_carousel_init();
     $("#todayspaper").attr("href","http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf?mode=mini&viewMode=doublePage&shareMenuEnabled=false&printButtonEnabled=false&shareButtonEnabled=false&searchButtonEnabled=false&folderId=f3714766-9353-4f5c-90fe-82933127ab16").fancybox( {
     'width': '600px',
     'height': '450px',
     'overlayColor': '#eee',
     'type': 'swf',
     'swf': {
        'allowscriptaccess': 'always',
        'menu': 'false',
        'wmode': 'transparent',
        'allowfullscreen': 'true'
    } });
   }

   if ($('#weekend').length > 0) {
     weekend_top_nav();
     $("#menu-top-nav > li:last-child a").fancybox( {
     'width': '600px',
     'height': '450px',
     'overlayColor': '#eee',
     'type': 'swf',
     'swf': {
        'allowscriptaccess': 'always',
        'menu': 'false',
        'wmode': 'transparent',
        'allowfullscreen': 'true'
    } });
   }

   if ($body.hasClass('category-opinion') ) {
     opinion_init();
   }

   if ($body.hasClass('page-template-multimedia-php')) {
    mult_content_init();
    equally_space_horizontally('ul[id^="menu-multimedia"]'); //spaces the links in the nav under the masthead
    console.log('Spacing mult nav bar');
    } else {
      equally_space_horizontally('#menu-primary'); //spaces the links in the nav under the masthead
      console.log('Spacing main nav bar');
    }
  }

  function mult_content_init() {
    mult_helper("multimedia");
    console.log('Content initialized.');
    // Click listeners for navbar 
    // On-click call mult_helper(with the relevant category);
    // Selector id must be of the ul element containing the navbar links
    $('ul[id^="menu-multimedia"] li > a').click(function(e){
      if (path != '/') {
        e.preventDefault();
      }
      var path = e.target.pathname;
      var lastSlash = path.lastIndexOf('/');
      if (path.indexOf('/') != path.length - 1) {
        var i;
        var slash;
        for (i = 0; i < path.length - 1 && (i = path.indexOf('/', i)) != lastSlash; i++ )
          slash = i;
        mult_helper(path.substr(slash + 1, lastSlash - slash - 1));
        console.log('Initializing content for: ' + path.substr(slash, lastSlash));
      } else {
        mult_helper(path.substr(lastSlash + 1));
        console.log('Initialized content for: ' + path.substr(lastSlash));
      }
    });
  }

    function mult_insert_posts(posts) {
        if(posts.length === 0) {
            console.log("No posts");
            return;
        }
        // Put the first post into the player
        var first = posts[0];
        console.log("Loading First Video");
        var htmlstr = "<iframe id=\"video-player\" src=\"http://youtube.com/embed/" + first.vid_id + "\" frameborder=0></iframe>";
        $("#main-theater").html(htmlstr);
        
        // Put the first post info into the appropriate place
        $("#theatre-video-title").html(first.title);
        $("#theatre-video-author").html(first.author);
        $("#theatre-video-excerpt").html(first.content);

        // Load the rest of the videos into the slider
        var i;
        htmlstr = "";
        for(i = 0; i < posts.length; i) {
            if(i === 0) {    // only the first 7 are active
                htmlstr += "<div class=\"item active\"><ul>\n";
            } else {
                htmlstr += "<div class=\"item\"><ul>\n";
            }
            var k = i + 7;
            for(i; i < k && i < posts.length; i++) {
                var p = posts[i];
                htmlstr += "<li>\n";
                htmlstr += "<div class=\"crop\" title=\"" + p.title + "\">\n";
                htmlstr += "<a href=\"#\" data-videoid=\"" + p.vid_id + "\" data-author=\"" + p.author + "\" rel=\"tooltip\" class=\"thumbnail-video\" title=\"" + p.title + "\">\n";
                htmlstr += "<p data-videoid=\"" + p.vid_id + "\" class=\"video-content\">" + p.content.replace(/<(?:.|\n)*?>/gm, '') + "</p>\n";
                htmlstr += "<img class=\"thumbnail-youtube\" title=\"" + p.title + "\" src=\"http://img.youtube.com/vi/" + p.vid_id + "/0.jpg\"/>\n";
                htmlstr += "</a>\n";
                htmlstr += "</div>\n";
                htmlstr += "</li>\n";
            }
            htmlstr += "</ul></div>\n";
        }
        $(".carousel-inner").html(htmlstr);
        tooltip_init();
        carousel_init();
        multimedia_selector();
    }

  function mult_helper(category) {
    var query;
    query = "?json=get_category_posts&count=21&post_type=video&category_slug=" + category;
    $.ajax({
      type: "GET",
      url: "http://yaledailynews.com/" + query
    }).always(function (data) {
      try {
        var json;
        if (data.responseText) {
          console.log('Response text parsing...');
          var st = data.responseText.indexOf('{');
          var nd = data.responseText.lastIndexOf('}');
          json = $.parseJSON(data.responseText.substring(st, nd + 1));
        } else {
          json = data;
        }
        if(json.status == "ok") {
          console.log("Updated");
          console.log("Response ok. Parsing.");
          var parsed_posts = [];
          for(var i = 0; i < json.count; i++) {
            var post = json.posts[i];
            var author = post.author.name;
            var title = post.title_plain;
            var tmp = post.content;
            var yt = tmp.indexOf("youtube");
            var qs = tmp.indexOf('?', yt);
            var tmp2 = tmp.substring(yt, (qs < 0) ? tmp.length : qs);
            var myregexp = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i;
            var id = tmp2.match(myregexp);
            id = id[1]; // get the video id
            var k = tmp.indexOf('\n');
            var content = tmp.substring(k + 1, tmp.length);
            var parsed = {
              author: author,
              title: title,
              vid_id: id,
              content: content
            };
            parsed_posts.push(parsed);
          }
          mult_insert_posts(parsed_posts);
        }
        } catch(e) {console.log("Error: could not pull posts for: " + category);} 
    });
  }

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
  }

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
  }

  function tooltip_init() {
  $('.crop').tooltip();  
  }

  function carousel_init() {
  $('.carousel').carousel();
  $('.carousel').carousel('pause');
  $('.left.carousel-control').click(function(e){ 
    $('.carousel').carousel('prev'); 
    e.preventDefault(); 
  }); 
  $('.right.carousel-control').click(function(e){ 
    $('.carousel').carousel('next'); 
    e.preventDefault(); 
  }); 
  $('#slider').hover(function() {
    $('.carousel-control').css('opacity', '.7'); 
    $('.carousel-control').css('filter', 'alpha(opacity=70)'); 
  }, function() {
    $('.carousel-control').css('opacity', '0'); 
    $('.carousel-control').css('filter', 'alpha(opacity=0)'); 
  });
  }

  function multimedia_selector() {
    $('.thumbnail-video').click(function(e){
      e.preventDefault();
      var videoId = e.currentTarget.attributes[1].value;
      var videoAuthor = e.currentTarget.attributes[2].value;
      var videoTitle = e.currentTarget.attributes.title.value;
      var videoExcerpt = $('p[data-videoid="' + videoId + '"]').html();
      $('#video-player').attr('src', 'http://www.youtube.com/embed/' + videoId);
      $('iframe').attr('src', $('iframe').attr('src')); // Reloads iFrame
      $('#theatre-video-author').html('by ' + videoAuthor);
      $('#theatre-video-title').html(videoTitle);
      $('#theatre-video-excerpt').html(videoExcerpt);
    });
  }

  $(document).ready( initialize );

} (jQuery) );
