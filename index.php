<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package Tiafeno
 * @subpackage wp_euromada
 * @since Euromada 1.0
 */

get_header(); ?>

<div id="primary-content">
      <div class="uk-section uk-section-large uk-padding-medium">
        <div class="uk-container uk-container-small">
        <?php if ( have_posts() ) : ?>
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
            else :
              echo 'No post!';
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
