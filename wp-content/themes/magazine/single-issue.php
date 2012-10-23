<?php get_header(); ?>
  <div class="span24">
    <!-- Top layer containing the top-stories from the issue -->
    <div class="row">
      <div class="span10" style="height: 500px; background-color: purple;"></div>
      <div class="span14">
          <div style="height: 153px; margin-bottom:20px; background-color: red;"></div>
          <div style="height: 153px; margin-bottom:20px; background-color: red;"></div>
          <div style="height: 153px; background-color: red;"></div>
      </div>
    </div>
    <!-- Lower half of the page: essays, cross-campus, etc. -->
    <h1>Essays</h1>
    <div class="row">
         <!-- begin left column: mag content -->
         <div class="span18" id="top-essays">
            <div style="height:230px; color: blue;">&ensp;</div>
            <div class="row">
              <div class="span9 item">essay 1</div>
              <div class="span9 item">essay 2</div> 
            </div>
         </div> 
         <!-- begin sidebar -->
         <div class="offset1 span5 sidebar-widgets">
            <?php if(function_exists('dynamic_sidebar')) { dynamic_sidebar('magazine_home'); } ?>
         </div>
    </div>
  </div>
<?php get_footer(); ?>
