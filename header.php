<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything.
 *
 * @package Tiafeno
 * @subpackage wp_euromada
 * @since Euromada 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>

  <style type="text/css">
  /** UI semantic */
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/semantic.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/dropdown.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/transition.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/dimmer.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/rating.css");
  /** UIkit */
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/uikit.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/style.css");
  /** Google fonts */
    @import url('https://fonts.googleapis.com/css?family=Exo:300,400,700');
  </style>
  <script type="text/javascript">
    (function($){
      $(document).ready(function() {
        $('.ui.dropdown').dropdown();
      });
    })(jQuery)
  </script>
  </head>

<body <?php body_class(); ?>>
	<header>
  	<div id="er-header-top" class="uk-content uk-content-small">
      <div class="er-navbar-top">

        <?php if ( has_nav_menu( "top_menu" ) ) : ?>
          <div class="uk-container uk-container-small">
            <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
            <?php
              wp_nav_menu( [
                'menu_class' => "uk-subnav uk-margin-remove",
                'theme_location' => 'top_menu',
                'container_class' => 'uk-navbar-right'
              ] );
            ?>
              <!-- <div class="uk-navbar-right">
                <ul class="uk-subnav uk-margin-remove">
                  <li><a href="#">Se connecter</a></li>
                  <li><a href="#">Condition de ventes</a></li>
                </ul>
              </div> -->
            </nav>
          </div>
        <?php endif; ?>

        </div>
      <div class="uk-container ukt-container-small">
        <div class="uk-margin-medium-bottom uk-margin-top" uk-grid>
          <div class="uk-width-1-2 er-logo">
            <!-- logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="uk-logo">
              <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.jpg" alt="<?php bloginfo( 'name' ); ?>" />
            </a>
          </div>	
        </div>
      </div>
    </div>
    <div id="er-header-nav" class="uk-container uk-container-small">
      <!-- main navigation -->
      <?php if ( has_nav_menu( 'primary' ) ) : ?>

        <nav class="uk-navbar-container uk-navbar-transparent main-navigation" style="margin-bottom: 15px" uk-navbar>
          <?php
            wp_nav_menu( [
              'menu_class' => "uk-subnav uk-subnav-pill er-subnav uk-margin",
              'theme_location' => 'primary',
              'container_class' => 'uk-navbar-right'
            ] );
          ?>
          <!-- <div class="uk-navbar-right">
            <ul class="uk-subnav uk-subnav-pill er-subnav" uk-margin>
              <li class="uk-active"><a href="#">Accueil</a></li>
              <li><a href="offres.html">Offres</a></li>
              <li><a href="#">Promotion</a></li>
              <li><a href="#">Nous contacter</a></li>
              <li class="er-publisher-car"><a href="#">Publier une voiture</a></li>
            </ul>
          </div> -->

          <div class="uk-navbar-right">
            <?php if ( has_nav_menu( 'account' )) : ?>
              <?php
                wp_nav_menu( [
                  'menu_class' => "uk-subnav uk-subnav-pill er-user-subnav uk-margin",
                  'theme_location' => 'account',
                  'container_class' => ' ',
                  'walker' => new Account_Walker()
                ] );
              ?>
              <!-- <ul class="uk-subnav uk-subnav-pill er-user-subnav" uk-margin>
                <li>
                  <a href="#">
                    <span class="er-user-icon" uk-icon="icon: user"></span> 
                    <span class="er-user-text">Mon compte</span>
                  </a>
                </li>
              </ul> -->
            <?php endif; ?>
          </div>
        </nav>

      <?php endif; ?>
    </div>
  </header>
  <div id="primary">
  
    <?php 
        if ( is_active_sidebar( 'middle-area' ) ) : 
          dynamic_sidebar( 'middle-area' ); 
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