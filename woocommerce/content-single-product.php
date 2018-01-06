<?php
if ( ! defined( 'ABSPATH' ) ) 
	exit; 

$euromada = new Euromada();
?>

<?php
  /**
   * woocommerce_before_single_product hook.
   *
   * @hooked wc_print_notices - 10
   */
  do_action( 'woocommerce_before_single_product' );
?>
<script type="text/javascript">
  var __advert__ = <?= json_encode( $euromada->getAdvert() ); ?>
</script>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div id="app-product" class="uk-container uk-container-small">
    <div v-if="access" uk-grid>

      <div class="uk-width-2-3@m">
        <div id="slider" class="uk-position-relative" uk-slideshow="animation: fade" style="margin-bottom: 7em;">
          <ul class="uk-slideshow-items">
            <li v-for="image in product.gallery.full">
              <img v-bind:src="image[0][0]" v-bind:alt="product.title" uk-cover>
            </li>
          </ul>

          <div class="uk-position-bottom-center uk-position-small" style="bottom: -10em;">
            <div>
              <ul class="uk-thumbnav">
                <li v-for="(image, index) in product.gallery.thumbnail" v-bind:uk-slideshow-item="index">
                  <a href="#">
                    <img v-bind:src="image[0][0]" width="100" v-bind:alt="product.title">
                  </a>
                </li>
              </ul>
            </div>

          </div>
        </div>
        <div class="uk-padding-large uk-padding-remove-left uk-padding-remove-right">
          <table class="ui celled table" v-if="product.attributes != undefined">
            <tbody>
              <tr class="er-product-specification">
                <td class="uk-text-uppercase">Spécification</td>
                <td></td>
              </tr>
              <tr v-for="(attribute, index) in product.attributes" v-bind:class="index % 2 ? activeClass : ''">
                <td>{{ attribute.taxonomy | formatName }}</td>
                <td>{{ attribute.name }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div>
          <h2 class="er-h2 er-Exo">Description</h2>
          <p v-html="product.description"></p>
          <p v-if="product.description == ''">Aucune description.</p>
        </div>
      </div>
      <div class="uk-width-1-3@m">
        <div itemscope itemtype="http://schema.org/Product">
          <psummary v-bind:product="product"></psummary>
        </div>

        <section class="uk-margin-top">
          <div class="ui centered er-card">
            <div class="content er-share-title">
              <p class="header uk-text-uppercase er-h2">PARTAGER L'ANNONCE</p>
            </div>
            <div class="content er-share-content">
              <div class="meta uk-flex">
                <social-media></social-media>
              </div>
            </div>
          </div>
        </section>

      </div>
    </div>

    <div id="app-other-publisher" class="uk-margin-large-top">
      <div class="uk-flex">
        <h2 class="uk-text-uppercase uk-text-center uk-margin-auto er-h2 er-underline">Autre annonces</h2>
      </div>
      <div class="uk-container uk-container-small uk-margin-top">
        <p class="uk-text-center uk-margin-auto uk-width-xlarge">Vous pouvez également consulter d’autres sites web à partir des liens ci-dessous. Communiquez-nous la référence
          que vous souhaitez commander et nous vous ferons un devis.</p>
        <div class="uk-width-xlarge uk-margin-auto" uk-grid>
          <div class="uk-width-1-4@s">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/leboncoin.jpg" />
          </div>
          <div class="uk-width-1-4@s">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/paruvendu.jpg" />
          </div>
          <div class="uk-width-1-4@s">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/mobile.de.jpg" />
          </div>
          <div class="uk-width-1-4@s">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/europe-camions.jpg" />
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

