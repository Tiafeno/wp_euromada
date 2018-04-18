<?php
/**
 * Template Name: Home page
 * 
 */
get_header();

$Euromada = new Euromada();
$adverts = $Euromada->getLastAd();
$product_categories = Services::getProductsCat();
?>
<script type="text/javascript">
  var __categories__ = <?= json_encode( $product_categories, JSON_PRETTY_PRINT ); ?>;
  var __adverts__ = <?= json_encode( $adverts, JSON_PRETTY_PRINT ); ?>;
</script>
      <?php 
          if ( is_active_sidebar( 'home-slider' ) ) : 
            dynamic_sidebar( 'home-slider' ); 
          endif; 
        ?>
      <?php if (is_front_page()) : ?>
        <section class="er-information uk-padding-medium uk-padding-remove-left uk-padding-remove-right">
          <!-- euromada description -->
          <div class="uk-section-transparent uk-section-large uk-margin-remove uk-padding-remove">
            <div class="uk-container uk-container-small">
              <h2 class="uk-text-uppercase">NOTRE MÉTIER EST D'ACHETER ET D’EXPÉDIER DES VOITURES D'OCCASION DE L'EUROPE VERS 
                MADAGASCAR.</h2>
              <p>Grâce à notre antenne installée en FRANCE : EUROMADA offre une meilleure solution pour les particuliers ou entreprises à Madagascar qui 
                cherchent des moyens sûrs et abordables pour acheter et importer les voitures de leur choix depuis l’Europe vers Madagascar.</p>
            </div>
          </div>
        </section>
      <?php endif; ?>
      <div id="primary-content">
        <div class="uk-section uk-section-large uk-padding-medium">
          <div class="uk-container uk-container-small">

              <div class="er-annonce-publish">
              <h2 class="uk-text-uppercase er-h2 uk-margin-remove-bottom">Les dernières annonces publiées</h2>
              <p class="uk-text-small uk-margin-remove-top">Voitures occasion</p>

              <ul id="er-annonce" class="uk-switcher uk-margin">
              <?php 
              $chuncky = array_chunk( $adverts, 4 ); 
              foreach ($chuncky as $key => $advert) :
              ?>
                <li class="<?= ($key == 0) ? 'uk-position-relative' : '' ?>">
                  <div class="ui special cards">

                  <?php foreach ( $advert as $adv ) :?>
                    <div class="card">
                      <div class="blurring dimmable image">
                        <div class="ui dimmer">
                          <div class="content">
                            <div class="center">
                                <div class="ui inverted button" onClick="redirect( '<?= $adv->url ?>' )">Voir</div>
                            </div>
                          </div>
                        </div>

                          <div class="er-card-image" style="
                                  background: #ffffff url('<?= $adv->imgLink ?>') no-repeat center center;
                                  background-size: contain;
                                  width: 213px;
                                  height: 160px;
                                  ">
                        </div>
                      </div>
                      <div class="content">
                        <a class="header er-h2"><?= $adv->title ?></a>
                        <div class="meta">
                            <span class="cost money"><?= $adv->cost ?></span>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>

                  </div>
                </li>
              <?php endforeach; ?>

              </ul>

              <div class="uk-flex uk-flex-center">
                <ul uk-switcher="animation: uk-animation-slide-right-medium; connect: #er-annonce;" class="uk-dotnav" >
                <?php foreach ($chuncky as $key => $adverts): ?>
                  <li><a href="#"><?= ($key == 0) ? 'Active' : 'Item' ?></a></li> 
                <?php endforeach; ?>
                </ul>
              </div>
            </div>

            <div id="app-benefit" class="er-annonce-benefit uk-margin-large-top">
              <h2 class="uk-text-uppercase er-h2 uk-margin-remove-bottom">FAITES VOTRE CHOIX</h2>
              <div class="uk-container uk-margin-medium-top">
                <div class="ui special cards">
                  <div class="card" v-for="benefit in benefits">
                    <div class="blurring dimmable image">
                      <div class="ui dimmer">
                        <div class="content">
                          <div class="center">
                            <div class="ui inverted button" @click="redirect(benefit.url)">Voir</div>
                          </div>
                        </div>
                      </div>
                      <img v-bind:src="benefit.image" v-bind:alt="benefit.name">
                    </div>
                    <div class="content">
                      <a v-bind:href="benefit.url" class="header er-h2 uk-text-uppercase"> {{ benefit.name }}</a>
                      <div class="meta">
                        <span class="date uk-text-uppercase">à partir de </span>
                        <p class="er-h2">{{ benefit.cost | money }} MGA</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="app-custom">
              <annonces></annonces>
            </div>

          </div>
        </div>
      </div>
      <?php get_footer(); ?>
    </div>
    <?php wp_footer(); ?>
  </div>
</body>
</html>