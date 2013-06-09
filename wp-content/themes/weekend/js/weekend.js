(function($) {
  var YDN = window.YDN || (window.YDN = {});

  function initialize() {
    var $body = $('body');
    //run the scripts for a single-post

   if ($body.hasClass('category-blog') ) {
     console.log('wdafsd');
     $('.blocks').infinitescroll({
      loading: {
        finished: undefined,
        finishedMsg: "<em>Congratulations, you've reached the end of the Earth.</em>",
        img: 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',
        msg: null,
        msgText: '<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div><em>Loading the next set of posts...</em>',
        selector: null,
        speed: 'fast',
        start: undefined },
      navSelector : 'div#nav-below',
      nextSelector : 'div#nav-below div.nav-previous a',
      itemSelector : 'div.block',
      bufferPx: 100
      });
   } 


  } // End of initialize


  function resize_items () {
    $('.content-item').each(function(){
     var item_height = $(this).height();
     var img_height = $(this).children('img').height();
     var height = (item_height > img_height) ? item_height : img_height; 
     $(this).css('height', height);
    });
  }

  $(document).ready( initialize );

} (jQuery) );

