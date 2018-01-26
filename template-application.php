
<?php
/**
 * Template Name: Application
 * 
 */

get_header(); ?>

      <div id="primary-content">
        <div class="uk-section uk-section-large uk-padding-medium">
          <div class="uk-container uk-container-small">
          <?php
            // Start the loop.
            while ( have_posts() ) : the_post();
              the_content();
            endwhile;
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