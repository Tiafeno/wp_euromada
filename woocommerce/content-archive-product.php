<?php
if ( ! defined( 'ABSPATH' ) ) 
  exit; 

global $wp_query;
$queried = $wp_query->get_queried_object();
if ($queried instanceof WP_Post_Type) :
  $args = [
    'post_type'      => $queried->name,
    'post_status'    => 'publish',
    'posts_per_page'  => -1
  ];
  $postsQueried = new WP_Query( $args );
  $count = $postsQueried->post_count;
  wp_reset_postdata();
endif;

$euromada = new Euromada();
$adverts = $euromada->getAdverts();
if ($queried instanceof WP_Term || is_null($queried))
  $count = count($adverts);

/**
 * Change badge label
 * Le nom de la variable est la post type
 */
$product = new stdClass();
$product->badge = "VOITURES D'OCCASION";



?>

<script type="text/javascript">
  var __adverts__ = <?= json_encode( $adverts, JSON_PRETTY_PRINT ); ?>;
  var __post_count__ = <?= (int)$count ?>;
</script>

<div>
  <div class="uk-margin-large-top" uk-grid>
    <div class="er-annonce-list uk-width-2-3@m">
      <div  id="app-lists">
        <h2 class="uk-text-uppercase er-h2 uk-margin-remove-bottom">Voitures occasion</h2>
        <p class="uk-text-small uk-margin-remove-top">{{ postCount | money }} {{ postCount == 1 ? 'annonce': 'annonces'}}</p>

        <!-- order select -->
        <div uk-grid>
          <div class="uk-width-1-3@s">
            <select name="orderBy" v-model="sorting" v-on:change="sortBy" class="ui fluid normal dropdown">
              <option value="">Trier par</option>
              <option value="title">Titre</option>
              <option value="cost">Prix</option>
            </select>
          </div>
        </div>
        <!-- end -->

        <div class="ui divided items">

          <div class="item" v-for="(advert, index) in adverts">
            <div class="image">
              <p class="er-photo">{{ advert.countPic }}</p>

              <div class="archive-thumbnail" :style="{
                'background': '#eae8e8 url(' + advert.imgLink + ') no-repeat center center',
                'background-size': 'contain'
              }">
              </div>

            </div>
            <div class="content">
              <div class="extra">
                <div class="ui left floated"><a v-bind:href="advert.url" class="header er-list-title">{{ advert.title }}</a></div>
                <div class="ui right floated er-h2" style="color: #000"><p>{{ advert.cost | euro }}</p></div>
              </div>
              <div class="meta">
                <span class="cinema">{{ advert.dateadd.date | moment }}</span>
              </div>
              <div class="description">
                <p></p>
              </div>
              <div class="extra">
                <div class="ui label er-label"><?= ${$badge}->badge ?></div>
                <div class="ui right floated primary button er-button-voir" @click="redirect(advert.url)">Voir</div>
              </div>
            </div>
          </div>
          
        </div>
      </div>

      <div class="er-pagination uk-margin-small-top">
        <?php
          $pages = paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'       => '?paged=%#%',
            'prev_text'    => '<span uk-pagination-previous></span>',
            'next_text'    => '<span uk-pagination-next></span>',
            'current' => max( 1, get_query_var( 'paged' ) ),
            'total'   => $wp_query->max_num_pages,
            'type'    => 'array'
          ));

          if ( is_array( $pages ) ) {
            $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
            echo '<ul class="uk-pagination uk-flex-center" uk-margin>';
            foreach ( $pages as $page ) echo "<li> $page </li>";
            echo '</ul>';
          }
        ?>
      </div>

    </div> <!-- /.er-annonce-list -->
    <div class="er-sidebar uk-width-1-3@m">
      <div>
        <?php
          $show = isset($recommandations) ? true : false;
          if ($show):
        ?>
        <!-- Recommandation -->
        <section id="recommandations" class="uk-display-inline-block uk-margin-bottom">
          <div class="ui centered card" style="box-shadow: none !important">
            <div class="content er-sidebar-title" style="border-radius: 0px !important">
              <span class="header uk-text-uppercase er-h2">Nos récommandation</span>
            </div>
          </div>


          <?php
            while ($recommandations->have_posts()): $recommandations->the_post();
              $cost = get_post_meta( $recommandations->post->ID, 'cost_recommandation', true );
              $thumbnail_url = get_the_post_thumbnail_url( $recommandations->post->ID, [100, 50] );
          ?>

          <div class="uk-margin-small" uk-grid>
            <div class="uk-width-1-3"> 
              <img class="uk-padding-small uk-padding-remove-right uk-padding-remove-vertical" width="77" src="<?= $thumbnail_url ?>"> 
            </div>
            <div class="uk-width-2-3 uk-flex">
              <div class="uk-margin-auto-vertical">
                <p class="uk-margin-remove uk-paddin-remove uk-text-uppercase uk-text-small">
                  <a href="<?= the_permalink( $recommandations->post ) ?>"><?= $recommandations->post->post_title ?></a>
                </p>
                <p class="uk-margin-remove uk-paddin-remove uk-text-meta money"><?= $cost ?></p>
              </div>
            </div>
          </div>

          <?php endwhile; ?>

        </section>
          <!-- end Recommandation -->
      <?php endif; ?>

        <!-- Promotion -->
        <section  id="app-promotion" v-show="products.length > 0" class="uk-margin-bottom">
          <div class="ui centered card" style="box-shadow: none !important">
            <div class="content er-sidebar-title">
              <a class="header uk-text-uppercase er-h2">les promotions</a>
            </div>
          </div>
          
          <div class="ui centered card" v-for="(product, index) in products">
            <div class="image">
                <img v-bind:src="product.imgLink">
            </div>
            <div class="content">
              <div class="meta">
                  <span class="date uk-text-uppercase er-h2">{{ product.title }}</span>
                  <p class="er-sidebar-cost">{{ product.cost | euro }}</p>
                </div>
            </div>
            <div class="extra content uk-flex">
              <div class="ui buttons uk-margin-auto">
                <div class="ui primary green button" @click="window.location.href = product.url">Voir</div>
              </div>
            </div>

          </div>

        </section>
        <!-- end promotion -->


        <!-- autres annonces -->
        <section class="uk-margin-bottom">
          <div style="box-shadow: none !important; padding: 15px; background: #000000; font-weight: bold;">
            <div class="content">
              <span class="header uk-text-uppercase er-h2" style="color: white">Autres annonces</span>
            </div>
          </div>
          
          <div class="uk-container uk-container-small uk-margin-top">
            <p class="uk-text-left uk-margin-auto uk-width-xlarge">Vous pouvez également consulter les sites web ci-dessous, il y a des millions des
            voitures à votre disposition. Communiquez-nous la référence que vous souhaitez commander et nous vous ferons un devis.</p>
            <div class="ui small images" >
              <div class="uk-inline er-other-sidebar-logo">
                <span class="website" data-url="https://www.leboncoin.fr/_vehicules_/offres/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/leboncoin.jpg" />
                </span>
              </div>
              <div class="uk-inline er-other-sidebar-logo">
                <span class="website" data-url="https://www.paruvendu.fr/voiture-occasion/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/paruvendu.jpg" />
                </span>
              </div>
              <div class="uk-inline er-other-sidebar-logo">
                <span class="website" data-url="https://www.mobile.de/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/mobile.de.jpg" />
                </span>
              </div>
              <div class="uk-inline er-other-sidebar-logo">
                <span class="website" data-url="https://www.europe-camions.com/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/europe-camions.jpg" />
                </span>
              </div>
              <div class="uk-inline er-other-sidebar-logo">
                <span class="website" data-url="https://www.lacentrale.fr/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/lacentrale.jpg" />
                </a>
              </div>
              <div class="uk-inline er-other-sidebar-logo">
                <span  class="website" data-url="https://www.automobile.fr/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/automobile.jpg" />
                </span>
              </div>
              <div class="uk-inline er-other-sidebar-logo">
                <span class="website" data-url="https://www.autoscout24.fr/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/auto_scout24.jpg" />
                </span>
              </div>
              <div class="uk-inline er-other-sidebar-logo">
                <span class="website" data-url="'https://www.aramisauto.com/">
                  <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/aramisauto.jpg" />
                </span>
              </div>

            </div>
          </div>

        </section>
        <!-- autres annonces -->
      </div>

    </div>
  </div>

</div>