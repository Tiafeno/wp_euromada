
<?php
/**
 * The template for displaying single content
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package Tiafeno
 * @subpackage wp_euromada
 * @since Euromada 1.0
 */

get_header(); 

?>

      <div id="primary-content">
        <div class="uk-section uk-section-large uk-padding-medium">
          <div class="uk-container uk-container-small">
          <?php
            // Start the loop.
            while ( have_posts() ) : the_post();
          ?>
              <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
              </header><!-- .entry-header -->
          <?php
              the_content();
            // End the loop.
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