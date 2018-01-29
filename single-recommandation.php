
<?php
get_header(); 

global $post;
$current_url = get_the_permalink(get_the_ID());
$title = get_the_title();
$cost = get_post_meta( $post->ID, 'cost_recommandation', true );
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
          <?php while ( have_posts() ) : the_post(); ?>
              
            <div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
              <div class="uk-container uk-container-small">
                <div uk-grid>

                  <div class="uk-width-2-3@m">
                    <div class="uk-margin-small-bottom">
                      <h2 itemprop="name" class="er-h2"><?= $post->post_title ?></h2>

                      <div class="uk-cover-container uk-height-medium">
                        <?= get_the_post_thumbnail($post->ID) ?>
                      </div>
                    </div>

                    <div>
                      <h2 class="er-h2 er-Exo">Description</h2>
                      <p><?php if (empty($post->post_content)) echo "Aucune description"; else echo $post->post_content; ?></p>
                    </div>
                  </div>
                  <div class="uk-width-1-3@m">
                    <div itemscope itemtype="http://schema.org/Product">
                      <div class="er-summary">
                        <div  itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                          <h1 itemprop="name" class="er-h1">NOS PRESTATIONS</h1>
                          <p class="price uk-margin-remove-top er-price money"><?= $cost ?></p>

                          <a href="?order_recommandation=<?= the_ID() ?>" class="ui button er-button-voir uk-margin-auto" style="display: table">
                            Commander
                            <span uk-icon="icon: chevron-right"></span>
                          </a>

                          <meta itemprop="price" content="<?= $cost ?>">
                          <meta itemprop="priceCurrency" content="EUR">
                          <meta itemprop="url" content="<?= get_permalink( $post ) ?>">
                          <link itemprop="availability" href="http://schema.org/InStock">
                        </div>
                      </div>
                    </div>

                    <section class="uk-margin-top">
                      <div class="ui centered er-card">
                        <div class="content er-share-title">
                          <p class="header uk-text-uppercase er-h2">PARTAGER L'ANNONCE</p>
                        </div>
                        <div class="content er-share-content">
                          <div class="meta uk-flex">
                            <div class="uk-margin-auto"> 
                              <a target="_blank" href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="uk-icon-button er-icon-button twitter-share-button" 
                              data-hashtags="euromada" data-text="<?= $title ?>"  uk-icon="icon: twitter"></a>
                              
                              <span class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" 
                                data-layout="button" data-size="large" data-mobile-iframe="false">
                                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= $current_url ?>" 
                                class="uk-icon-button er-icon-button fb-xfbml-parse-ignore" uk-icon="icon: facebook"></a>
                              </span>
                        
                              <a href="https://plus.google.com/share?url=<?= $current_url ?>" onclick="javascript:window.open(this.href,
                          '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button er-icon-button" 
                              data-action="share" uk-icon="icon: google-plus"></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>

                  </div>
                </div>

              </div>
            </div>

          <?php endwhile; ?>
          </div>
        </div>
      </div>
      <?php get_footer(); ?>
    </div>
    <?php wp_footer(); ?>
  </div>
</body>
</html>