<?php
if ( ! defined( 'ABSPATH' ) ) 
  exit; 
?>

<?php $euromada = new Euromada(); ?>
<script type="text/javascript">
  var __adverts__ = <?= json_encode( $euromada->getAdverts(), JSON_PRETTY_PRINT ); ?>
</script>

<div id="app-lists">
  <h2 class="uk-text-uppercase er-h2 uk-margin-remove-bottom">Voitures occasion</h2>
  <p class="uk-text-small uk-margin-remove-top">{{ postCount | money }} {{ postCount == 1 ? 'annonce': 'annonces'}}</p>

  <!-- order select -->
  <div uk-grid>
    <div class="uk-width-1-5@s">
      <select name="orderBy" v-model="sorting" v-on:change="sortBy" class="ui fluid normal dropdown">
        <option value="">Trier par</option>
        <option value="title">Titre</option>
        <option value="cost">Prix</option>
      </select>
    </div>
  </div>
  <!-- end -->

  <div class="uk-margin-large-top" uk-grid>
    <div class="er-annonce-list uk-width-2-3@m">

      <div class="ui divided items">

        <div class="item" v-for="(advert, index) in adverts">
          <div class="image">
            <p class="er-photo">{{ advert.countPic }}</p>
            <img v-bind:src="advert.imgLink" v-bind:alt="advert.title">
          </div>
          <div class="content">
            <div class="extra">
              <div class="ui left floated"><a class="header er-list-title">{{ advert.title }}</a></div>
              <div class="ui right floated er-h2" style="color: #000"><p>{{ advert.cost | ariary }}</p></div>
            </div>
            <div class="meta">
              <span class="cinema">{{ advert.dateadd.date }}</span>
            </div>
            <div class="description">
              <p></p>
            </div>
            <div class="extra">
              <div class="ui label er-label">Badget</div>
              <div class="ui right floated primary button er-button-voir" @click="window.location.href = advert.url">Voir</div>
            </div>
          </div>
        </div>

        <div class="er-pagination">
          <pagination></pagination>
          <?php
            the_posts_pagination( array(
              'mid_size'  => 2,
              'prev_text' => __( 'Back', 'textdomain' ),
              'next_text' => __( 'Onward', 'textdomain' ),
            ) );
          ?>
        </div>

      </div>

    </div> <!-- /.er-annonce-list -->
    <div class="er-sidebar uk-width-1-3@m">
      <!-- Promotion -->
      <section>
        <div class="ui centered card">
          <div class="content er-sidebar-title">
              <a class="header uk-text-uppercase er-h2">les promotions</a>
          </div>
          <div class="image">
              <img src="img/products/auto.png">
          </div>
          <div class="content">
            <div class="meta">
                <span class="date uk-text-uppercase er-h2">NISSAN QASHQAI S 2017</span>
                <p class="er-sidebar-cost">20.000.000 MGA</p>
              </div>
          </div>
          <div class="extra content uk-flex">
              <div class="ui buttons uk-margin-auto">
                <div class="ui primary green button">Voir tous</div>
              </div>
            </div>
        </div>
      </section>
      <!-- end promotion -->

    </div>
  </div>

</div>