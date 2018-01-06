<?php

get_header();

?>
    <div id="primary-content">
      <div class="uk-section uk-section-large uk-padding-medium">
        <div class="uk-container uk-container-small">
        <?php
          if ( have_posts() ) :
            wc_get_template_part( 'content', 'archive-product' ); 
          endif;
        ?>
        </div>
      </div>
    </div>
    <?php get_footer(); ?>
  </div>
  <?php wp_footer(); ?>
</body>
</html>