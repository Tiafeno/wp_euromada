<?php

get_header();

?>
      <div id="primary-content">
        <div class="uk-section uk-section-large uk-padding-medium">
          <div class="uk-container uk-container-small">
          <?php
            if ( have_posts() ) :
              set_query_var('badge', get_post_type());
              wc_get_template_part( 'content', 'archive-product' ); 
            else:
              echo '<h2 class="er-h2">Aucune annonce disponible pour le moment !</h2>';
            endif;
          ?>
          </div>
        </div>
      </div>
      <?php get_footer(); ?>
    </div>
    <?php wp_footer(); ?>
  </div>
</body>
</html>