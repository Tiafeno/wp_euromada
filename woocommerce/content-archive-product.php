<?php
if ( ! defined( 'ABSPATH' ) ) 
  exit; 

global $wp_query;
?>

<?php $euromada = new Euromada(); ?>
<script type="text/javascript">
  var __adverts__ = <?= json_encode( $euromada->getAdverts(), JSON_PRETTY_PRINT ); ?>
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
              <img v-bind:src="advert.imgLink" v-bind:alt="advert.title">
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
                <div class="ui label er-label"><?= strtoupper($badge) ?></div>
                <div class="ui right floated primary button er-button-voir" @click="window.location.href = advert.url">Voir</div>
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
        <section id="recommandations" class="uk-display-inline-block">
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
        <section  id="app-promotion" v-show="products.length > 0">
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
      </div>

    </div>
  </div>

</div>