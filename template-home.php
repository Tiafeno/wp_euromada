<?php
/**
 * Template Name: Home page
 * 
 */
get_header();

$Euromada = new Euromada();
$Adverts = $Euromada->getLastAd();
?>
<script type="text/javascript">
  var __adverts__ = <?= json_encode( $Adverts, JSON_PRETTY_PRINT ); ?>;
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
              <p>Grâce à notre antenne installée en FRANCE : EUROMADA offre une meilleure solution pour les particuliers ou entreprises à Madagascar qui 
                cherchent des moyens sûrs et abordables pour acheter et importer les voitures de leur choix depuis l’Europe vers Madagascar.</p>
            </div>
          </div>
        </section>
      <?php endif; ?>
      <div id="primary-content">
        <div class="uk-section uk-section-large uk-padding-medium">
          <div class="uk-container uk-container-small">

            <div id="app-publisher" class="er-annonce-publish">
              <h2 class="uk-text-uppercase er-h2 uk-margin-remove-bottom">Les dernières annonces publiées</h2>
              <p class="uk-text-small uk-margin-remove-top">Voitures occasion</p>

              <ul id="er-annonce" class="uk-switcher uk-margin">

                <li v-for="(advert, index) in adverts" v-bind:class="index == 0 ? 'uk-position-relative': ''">
                  <div class="ui special cards">
                    <div class="card" v-for="adv in advert">
                      <div class="blurring dimmable image">
                        <div class="ui dimmer">
                          <div class="content">
                            <div class="center">
                              <div class="ui inverted button" @click="onClick( adv.url )">Voir</div>
                            </div>
                          </div>
                        </div>
                        <img v-bind:src="adv.imgLink">
                      </div>
                      <div class="content">
                        <a class="header er-h2">{{ adv.title }}</a>
                        <div class="meta">
                          <span class="cost">{{ adv.cost | euro }} </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

              </ul>

              <div class="uk-flex uk-flex-center">
                <div uk-switcher="animation: uk-animation-slide-right-medium; connect: #er-annonce;" class="uk-dotnav" >
                    <li v-for="(advert, index) in adverts" v-bind:class="index == 0 ? 'uk-active' : ''"><a href="#">Item {{index}}</a></li>
                </div>
              </div>
            </div>

            <div id="app-benefit" class="er-annonce-benefit uk-margin-large-top">
              <h2 class="uk-text-uppercase er-h2 uk-margin-remove-bottom">NOS PRESTATIONS</h2>
              <div class="uk-container uk-margin-medium-top">
                <div class="ui special cards">
                  <div class="card" v-for="benefit in benefits">
                    <div class="blurring dimmable image">
                      <div class="ui dimmer">
                        <div class="content">
                          <div class="center">
                            <div class="ui inverted button">Voir</div>
                          </div>
                        </div>
                      </div>
                      <img v-bind:src="benefit.link" v-bind:alt="benefit.title">
                    </div>
                    <div class="content">
                      <a class="header er-h2 uk-text-uppercase"> {{ benefit.title }}</a>
                      <div class="meta">
                        <span class="date uk-text-uppercase">à partir de </span>
                        <p class="er-h2">{{ benefit.cost | money }} MGA</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="app-other-publisher" class="uk-margin-large-top">
              <div class="uk-flex"><h2 class="uk-text-uppercase uk-text-center uk-margin-auto er-h2 er-underline">Autre annonces</h2></div>
              <div class="uk-container uk-container-small uk-margin-top">
                <p class="uk-text-center uk-margin-auto uk-width-xlarge">Vous pouvez également consulter d’autres sites web à partir des liens ci-dessous.
                  Communiquez-nous la référence que vous souhaitez commander et nous vous ferons un devis.</p>
                <div class="uk-width-xlarge uk-margin-auto" uk-grid>
                  <div class="uk-width-1-4@s"><img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/leboncoin.jpg" /></div>
                  <div class="uk-width-1-4@s"><img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/paruvendu.jpg" /></div>
                  <div class="uk-width-1-4@s"><img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/mobile.de.jpg" /></div>
                  <div class="uk-width-1-4@s"><img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/europe-camions.jpg" /></div>

                </div>
              </div>
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