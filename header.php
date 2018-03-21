<?php
global $MESSAGE;
$min_price = get_option( "min_price", 1 );
$max_price = get_option( "max_price", 20000 );
$interval = [];

for ($i = $min_price; $i <= $max_price; $i += $min_price)
  array_push($interval, $i);

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
<!-- *

                                                                                                                                  
                                                                                                                                  
FFFFFFFFFFFFFFFFFFFFFF                lllllll   iiii                                                                              
F::::::::::::::::::::F                l:::::l  i::::i                                                                             
F::::::::::::::::::::F                l:::::l   iiii                                                                              
FF::::::FFFFFFFFF::::F                l:::::l                                                                                     
  F:::::F       FFFFFFaaaaaaaaaaaaa    l::::l iiiiiii     ccccccccccccccccrrrrr   rrrrrrrrr       eeeeeeeeeeee    aaaaaaaaaaaaa   
  F:::::F             a::::::::::::a   l::::l i:::::i   cc:::::::::::::::cr::::rrr:::::::::r    ee::::::::::::ee  a::::::::::::a  
  F::::::FFFFFFFFFF   aaaaaaaaa:::::a  l::::l  i::::i  c:::::::::::::::::cr:::::::::::::::::r  e::::::eeeee:::::eeaaaaaaaaa:::::a 
  F:::::::::::::::F            a::::a  l::::l  i::::i c:::::::cccccc:::::crr::::::rrrrr::::::re::::::e     e:::::e         a::::a 
  F:::::::::::::::F     aaaaaaa:::::a  l::::l  i::::i c::::::c     ccccccc r:::::r     r:::::re:::::::eeeee::::::e  aaaaaaa:::::a 
  F::::::FFFFFFFFFF   aa::::::::::::a  l::::l  i::::i c:::::c              r:::::r     rrrrrrre:::::::::::::::::e aa::::::::::::a 
  F:::::F            a::::aaaa::::::a  l::::l  i::::i c:::::c              r:::::r            e::::::eeeeeeeeeee a::::aaaa::::::a 
  F:::::F           a::::a    a:::::a  l::::l  i::::i c::::::c     ccccccc r:::::r            e:::::::e         a::::a    a:::::a 
FF:::::::FF         a::::a    a:::::a l::::::li::::::ic:::::::cccccc:::::c r:::::r            e::::::::e        a::::a    a:::::a 
F::::::::FF         a:::::aaaa::::::a l::::::li::::::i c:::::::::::::::::c r:::::r             e::::::::eeeeeeeea:::::aaaa::::::a 
F::::::::FF          a::::::::::aa:::al::::::li::::::i  cc:::::::::::::::c r:::::r              ee:::::::::::::e a::::::::::aa:::a
FFFFFFFFFFF           aaaaaaaaaa  aaaalllllllliiiiiiii    cccccccccccccccc rrrrrrr                eeeeeeeeeeeeee  aaaaaaaaaa  aaaa
                                                                                                                                  
                                                                                                                                  
                                                                                                                                  

*** Visite www.falicrea.com pour plus d'information et voir nos réalisations
*** Contact: contact@falicrea.com
  -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>

  <style type="text/css">
   
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/semantic.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/dropdown.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/transition.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/dimmer.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/rating.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/sidebar.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/icon.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/modal.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/js/venobox/venobox.css"); 
    
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/uikit.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/style.css");
    
    @import url('https://fonts.googleapis.com/css?family=Exo:300,400,700');

  </style>
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
  <script type="text/javascript">
    var __priceInterval__ = [ <?= implode(',', $interval); ?> ]; 
    (function($){
      $(document).ready(function() {
        $('.ui.dropdown').dropdown();

        /** Fix uk-active parent menu item */
        var elementActive = $(".uk-dropdown").find("li.uk-active");
        elementActive
          .parents(".menu-item-has-children")
          .children("a")
          .addClass("uk-active");

        function redirect( url ) {
          window.location.href = url;
        }
      });
    })(jQuery)
  </script>

  <style type="text/css">
    .er-dropdown {
      padding: 0 !important;
      background-color: #aaaaaa;
    }
    a.uk-active {
      background: #001689 !important;
    }
    #app-other-publisher .ui.small.images .image, 
    #app-other-publisher .ui.small.images img, 
    #app-other-publisher .ui.small.images svg, 
    #app-other-publisher .ui.small.image,

    .er-sidebar .ui.small.images .image, 
    .er-sidebar .ui.small.images img, 
    .er-sidebar .ui.small.images svg, 
    .er-sidebar .ui.small.image
    {
      width: 75px;
    }
    span.website {
      display: inherit;
    }

    .ui.form textarea, 
    .ui.form input:not([type]), 
    .ui.form input[type="date"], 
    .ui.form input[type="datetime-local"], 
    .ui.form input[type="email"], 
    .ui.form input[type="number"], 
    .ui.form input[type="password"], 
    .ui.form input[type="search"], 
    .ui.form input[type="tel"], 
    .ui.form input[type="time"], 
    .ui.form input[type="text"], 
    .ui.form input[type="file"], 
    .ui.form input[type="url"] {
      vertical-align: top;
      box-sizing: border-box !important;
    }
    form .ui.dropdown {
      box-sizing: border-box;
    }
    nav .er-navbar-right {
      margin-left: 15px !important;
    }
    @font-face {
      font-family: 'Icons';
      src: url("<?=  esc_url( get_template_directory_uri() ); ?>/css/themes/default/assets/fonts/icons.eot");
      src: url("<?=  esc_url( get_template_directory_uri() ); ?>/css/themes/default/assets/fonts/icons.eot?#iefix") format('embedded-opentype'), 
      url("<?=  esc_url( get_template_directory_uri() ); ?>/css/themes/default/assets/fonts/icons.woff2") format('woff2'), 
      url("<?=  esc_url( get_template_directory_uri() ); ?>/css/themes/default/assets/fonts/icons.woff") format('woff'), 
      url("<?=  esc_url( get_template_directory_uri() ); ?>/css/themes/default/assets/fonts/icons.ttf") format('truetype'), 
      url("<?=  esc_url( get_template_directory_uri() ); ?>/css/themes/default/assets/fonts/icons.svg#icons") format('svg');
      font-style: normal;
      font-weight: normal;
      font-variant: normal;
      text-decoration: inherit;
      text-transform: none;
    }
    #offcanvas-overlay .uk-active > a {
      color: #fff !important;
    }
    .ui.selection.dropdown .menu > .item, .ui.dropdown > .text {
      font-size: 12.5px !important;
      padding-right: 0px !important;
    }
    .er-other-logo:hover {
      /*border: 1px dashed #ddd8d8;*/
    }
    .er-other-sidebar-logo {
      /* width: 30%; */
    }
    #app-other-publisher .images {
      width: 100% !important;
    }
    #app-other-publisher .images img {
      width: 150px !important;
      margin: auto;
    }
    #recommandations {
      background: #003b81;
      width: 100%;
    }
    #recommandations a {
      color: white;
    }
    #recommandations p, #recommandations a {
      font-size: 11px;
    }
    #recommandations p:not(.money) {
      color: white !important;
    }
    .er-horaire .item > .content {
      color: white;
    }

    .archive-thumbnail {
      width: 100%;
      height: 135px;
    }
    .woocommerce #respond input#submit.alt, 
    .woocommerce a.button.alt, 
    .woocommerce button.button.alt, 
    .woocommerce input.button.alt {
      background-color: #001689;
    }

    .woocommerce #respond input#submit.alt:hover, 
    .woocommerce a.button.alt:hover, 
    .woocommerce button.button.alt:hover, 
    .woocommerce input.button.alt:hover {
      background-color: #f6c800;
    }
    
    @media only screen and (max-width: 767px) {
      .archive-thumbnail {
        width: 23em;
        height: 16em;
      }
    }
  </style>

  </head>

<body <?php body_class(); ?>>
  <div class="uk-offcanvas-content">
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
              
              </nav>
            </div>
          <?php endif; ?>

          </div>
        <div class="uk-container ukt-container-small">
          <div class="uk-margin-medium-bottom uk-margin-top" uk-grid>
            <div class="uk-width-1-2@m er-logo">
              <!-- logo -->
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="uk-logo">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.jpg" alt="<?php bloginfo( 'name' ); ?>" />
              </a>
            </div>

            <div class="uk-width-1-2@m">
              <?php 
                if ( is_active_sidebar( 'bannier-top' ) ) : 
                  dynamic_sidebar( 'bannier-top' ); 
                endif; 
              ?>
            </div>

          </div>
        </div>
      </div>
      <div id="er-header-nav" class="uk-container uk-container-small">
        <!-- main navigation -->
        <?php if ( has_nav_menu( 'primary' ) ) : ?>

          <nav class="uk-navbar-container uk-navbar-transparent main-navigation" style="margin-bottom: 15px; margin-right: 9px" uk-navbar>
            <div class="uk-margin-auto-left uk-flex">
              <?php
                wp_nav_menu( [
                  'menu_class' => "uk-subnav uk-subnav-pill er-subnav uk-margin er-navbar-right uk-visible@m",
                  'theme_location' => 'primary',
                  'container_class' => 'uk-navbar-right',
                  'walker' => new Primary_Walker
                ] );

                $usr = is_user_logged_in() ? wp_get_current_user() : 'Mon Compte';
                $profil_page_id = get_option( "profil_page_id", false );
                $profil_url = $usr instanceof WP_User ? get_the_permalink( (int)$profil_page_id ) : get_the_permalink( get_option( 'login_page_id' ) );
                $profil_name = $usr instanceof WP_User ? $usr->display_name  : $usr;
              ?>

              <div class="uk-navbar-right uk-hidden@m">
                <button class="uk-button uk-button-default" type="button" uk-toggle="target: #offcanvas-overlay">MENU</button>
                <div id="offcanvas-overlay" uk-offcanvas="overlay: true; mode: push">
                
                  <?php
                  wp_nav_menu( [
                    'menu_class' => "uk-nav uk-nav-primary uk-nav-center uk-margin-auto-vertical",
                    'theme_location' => 'primary',
                    'container_class' => 'uk-offcanvas-bar uk-flex uk-flex-column',
                    'walker' => new Primary_offcanvas_Walker
                  ] );
                  ?>

                </div>
              </div>

              <div class="uk-navbar-right er-navbar-right">
                <div class=" ">
                  <ul id="menu-compte" class="uk-subnav uk-subnav-pill er-user-subnav uk-margin">
                    <li>
                      <a href='<?= $profil_url ?>'><span class='er-user-icon' uk-icon='icon: user'></span><span class='er-user-text'><?= $profil_name ?></span></a> 
                    </li>
                  </ul>
                </div>
              </div>
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
      <?php if ( ! is_null($MESSAGE)) : ?>
          <div class="uk-section uk-section-large uk-padding-small ">
            <div class="uk-container uk-container-small">
              <div class="ui <?= $MESSAGE->type ?> message">
                <div class="header">
                <?= $MESSAGE->get_title() ?>
                </div>
                <p><?= $MESSAGE->get_message() ?></p>
              </div>
            </div>
          </div>
      <?php endif; ?>
    