<?php
if ( ! defined( 'ABSPATH' ) ) 
  exit; 

$current_url = get_the_permalink(get_the_ID());
$title = get_the_title();

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
    <div uk-grid>

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
          <table class="ui celled table uk-margin-top" v-if="product.attributes != undefined">
            <tbody>
              <tr class="er-product-specification">
                <td class="uk-text-uppercase">Spécification</td>
                <td></td>
              </tr>
              <tr v-for="(attribute, index) in product.attributes" :key="attribute.taxonomy" v-bind:class="index % 2 ? activeClass : ''">
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
                <div class="uk-margin-auto uk-flex"> 

                  <span style="margin: auto; position: relative; "><!-- bottom: -2px; margin-right: 10px; -->
                    <a target="_blank" href="https://twitter.com/share?ref_src=<?= $current_url ?>" class="uk-icon-button er-icon-button twitter-share-button" 
                    data-hashtags="euromada" data-url='<?= $current_url ?>' data-text="<?= $title ?>"><i class="twitter icon"></i></a>
                  </span>
                  
                  <span class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" 
                    data-layout="button" data-size="large" data-mobile-iframe="false">
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= $current_url ?>" 
                    class="uk-icon-button er-icon-button fb-xfbml-parse-ignore" ><i class="facebook f icon"></i></a>
                  </span>

                  <a href="https://plus.google.com/share?url=<?= $current_url ?>" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button er-icon-button" 
                  data-action="share" ><i class="google plus icon"></i></a>

                </div>
              </div>
            </div>
          </div>
        </section>

      </div>
    </div>
    <annonces></annonces>
  </div>
</div>

