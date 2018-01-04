<?php
add_action( "wp_head", function(){
  include get_template_directory() . '/inc/x-template.php';
}, 10, 2 );
get_header() ?>


    <div id="primary-content">
      <div class="uk-section uk-section-large uk-padding-medium">
        <div class="uk-container uk-container-small">
        <?php
          while ( have_posts() ) : the_post();
            wc_get_template_part( 'content', 'single-product' ); 
          endwhile;
        ?>
        </div>
      </div>
    </div>
    <?php get_footer(); ?>
  </div>
  <?php wp_footer(); ?>
</body>
</html>