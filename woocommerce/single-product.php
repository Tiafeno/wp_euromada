<?php

add_action('wp_head', function() {
  global $post;
  $thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
  $image = wp_get_attachment_url( (int)$thumbnail_id );
  $current_url = get_the_permalink(get_the_ID());
?>
  <meta property="og:locale" content="fr_FR" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="<?= get_the_title() ?>" />
  <meta property="og:description" content="<?= sanitize_textarea_field( $post->post_content ) ?>" />
  <meta property="og:url" content="<?= $current_url ?>" />
  <meta property="og:site_name" content="EUROMADA" />
  <meta property="og:image" content="<?= $image ?>" />

<?php
}, 10, 2);

get_header();

?>


<style type="text/css">
  .er-price {
    font-size: 15pt;
    font-weight: bold;
    display: block;
    width: 100%;
  }

  /** override css style */

  .ui.primary.buttons .button,
  .ui.primary.button {
    background-color: #001689 !important;
  }

  .er-button-voir:hover {
    background-color: #f6bf11 !important;
    color: #000000 !important;
  }

  .er-button-voir {
    background-color: #001689 !important;
    font-size: 14px !important;
    border-radius: 0 !important;
    color: #ffffff !important;
  }

  .er-button-voir i {
    visibility: hidden;
  }

  .er-button-voir:hover i {
    visibility: visible
  }

  .er-sidebar section .extra.content .ui.button {
    padding: 11px !important;
  }

  .summary {
    background: #E5E5E5;
    padding-left: 15px;
    padding-right: 15px;
    padding-top: 25px;
    padding-bottom: 30px;
  }

  .ui.celled.table tr th,
  .ui.celled.table tr td {
    border-left: none !important;
  }

  .ui.table {
    border: none !important;
  }

  tr.er-product-specification {
    background-color: #001689;
    color: aliceblue;
  }
  
  
  .er-Exo {
    font-family: "Exo", sans-serif;
  }

  .er-icon-button {
    border-radius: 0px !important;
  }

  .content.er-share-title p {
    color: #ffffff;
  }

  .content.er-share-title {
    padding: 10px 20px;
    background-color: #000000;
  }

  .er-card .meta {
    padding-top: 10px;
  }

  .er-share-content {
    border-bottom: 1px solid #DAD0C6;
    padding-bottom: 9px;
    border-left: 1px solid #DAD0C6;
    border-right: 1px solid #DAD0C6;
  }
</style>

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
  </div>
</body>
</html>