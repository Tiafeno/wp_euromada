<?php
/**
 * Author: Tiafeno Finel
 * Organisation: Entreprise FALI (Falicrea)
 * Author mail: tiafenofnel@gmail.com
 */

 /************
  * get_option( 'woocommerce_shop_page_id' ); 
    get_option( 'woocommerce_cart_page_id' ); 
    get_option( 'woocommerce_checkout_page_id' );
    get_option( 'woocommerce_pay_page_id' ); 
    get_option( 'woocommerce_thanks_page_id' ); 
    get_option( 'woocommerce_myaccount_page_id' ); 
    get_option( 'woocommerce_edit_address_page_id' ); 
    get_option( 'woocommerce_view_order_page_id' ); 
    get_option( 'woocommerce_terms_page_id' ); 
  */

$MESSAGE = null;

require get_template_directory() . '/inc/class-euromada.php';
require get_template_directory() . '/inc/class-services.php';
require get_template_directory() . '/inc/class-walker.php';
require get_template_directory() . '/inc/class-order.php';
require get_template_directory() . '/inc/class-message.php';
/** Shortcode */
require get_template_directory() . '/inc/shortcode/class-login.php';
require get_template_directory() . '/inc/shortcode/class-register.php';
require get_template_directory() . '/inc/shortcode/class-profil.php';
/** Widget */
require get_template_directory() . '/inc/widgets/search.widget.php';

$instanceEuromada = new Euromada();

add_action( 'after_setup_theme', function() {
  if ( !current_user_can( 'administrator' ) && !is_admin() ) {
    show_admin_bar( false );
  }
});

 /** On load wordpress */
 add_action( "wp_loaded", function() {
  global $MESSAGE, $instanceEuromada;
  // $user_ = new WP_User(3);
  // echo get_password_reset_key( $user_ );
  if (isset($_POST[ 'edit_profil_nonce' ]) &&
  wp_verify_nonce($_POST[ 'edit_profil_nonce' ], 'edit_profil') &&
  is_user_logged_in()) {
    $instanceEuromada->update_user();
  }
  
  /** Check if login form is submit */
  if (isset($_POST[ 'euromada_settings_nonce' ]) &&
  wp_verify_nonce($_POST[ 'euromada_settings_nonce' ], 'euromada_settings') &&
  is_admin() ) {

    $login_page_id = Services::getValue('login_page', '');
    update_option( 'login_page_id', $login_page_id );

    $register_page_id = Services::getValue('register_page', '');
    update_option( 'register_page_id', $register_page_id );

    $profil_page_id = Services::getValue('profil_page', '');
    update_option( 'profil_page_id', $profil_page_id );
  }

  /** Check if register form is submit */
  if (isset($_POST[ 'register_nonce' ]) &&
  wp_verify_nonce($_POST[ 'register_nonce' ], 'register') ) {
    $results = $instanceEuromada->register_user();
    $singin = (object)$results;
    $type =  ((bool)$singin->success == false) ? 'negative' : 'positive';
    $MESSAGE = new Euromada_Message($singin->msg, 'Inscription', $type);
  }

});

/** On login fail */
add_action( 'wp_login_failed', function() {
  $referer = $_SERVER[ 'HTTP_REFERER' ];
  // if there's a valid referrer, and it's not the default log-in screen
  if ( !empty($referer) && !strstr($referer, 'wp-login') && !strstr($referer, 'wp-admin') ) {
    exit(wp_redirect( $referer . '?login=failed', 301 ));  // let's append some information (login=failed) to the URL for the theme to use
  }
});

function euromada_init() {
  global $instanceEuromada;
  add_action("euromada_save_meta_user", [ $instanceEuromada, 'action_euromada_save_meta_user' ], 10, 1);
  add_action("euromada_update_information_user", [ $instanceEuromada, 'action_euromada_update_information_user' ], 10, 1);

  add_action( 'admin_init', function() {

    $advertiser = get_role( "advertiser" );
    if ($advertiser === null) {
      $role = Euromada::createRole();
    }

    $redirect = isset( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ] : home_url( '/' );
    if ( is_admin() && !defined( 'DOING_AJAX' ) && current_user_can( 'advertiser' ) ) {
      exit( wp_redirect( $redirect, 301 ) );
    }
  }, 100 );
  
  add_action( 'admin_menu', function() {
    add_meta_box( 'products', 'Produits', "render_product", "recommandation", 'normal', 'low' );
    add_menu_page('euromada', 'Euromada', 'manage_options', 'euromada', array(new Euromada, 'euromada_admin_template'), 'dashicons-admin-settings');
  });

  Euromada::taxonomy();
  Euromada::setRecommandation();
}
add_action( 'init', 'euromada_init' );

/**
 * Redirect in home page if user is login
 */
add_action( 'get_header', function() {
  global $post, $posts;

  /**
   * Si la variable $_GET 'order' existe
   * On ajoute le produit qui contient l'identifiant dans le panier et redirection 
   * vers la page panier pour faire la commande.
   */
  $order = Services::getValue("order", false); 
  $order_recommandation = Services::getValue("order_recommandation", false);
  if ($order) {
    $Order = new Euromada_Order();
    $Order->addCart( (int)$order );
    $cart_page_url = get_the_permalink( get_option( 'woocommerce_cart_page_id' ) );
    wp_redirect( $cart_page_url, 301 );
  }
  
  if ($order_recommandation) {
    $Order = new Euromada_Order();
    $product_id = Euromada_Order::createProduct( (int)$order_recommandation );
    if (is_wp_error( $product_id )) {
      echo $product_id->get_error_message();
    } else {
      /** Order product */
      $Order->addCart( $product_id );
      $cart_page_url = get_the_permalink( get_option( 'woocommerce_cart_page_id' ) );
      wp_redirect( $cart_page_url, 301 );
    }
    
  }
  /**
   * Si la variable $_GET __post_delete_id existe
   * on deplace le post dans la corbeille
   */
  $post_delete_id = Services::getValue('__post_delete_id', false);
  if (false != $post_delete_id) Euromada_profil::deletePost( (int)$post_delete_id );

  /** Verify header */
  if (is_user_logged_in()) {
    if ($post == null) return;
    $login_page_id = get_option( 'login_page_id', false );
    $profil_page_id = get_option( 'profil_page_id', false );
    if (is_int( (int)$login_page_id ) ) :
      /**
       * On verifie si la page actuel n'est pas une page pour se connecter.
       * Si non, on reste dans cette page.
       */
      if ($post->ID != (int)$login_page_id) return true;

      /** rediriger vers la page profil si l'identification existe sinon,
       * redirection vers la page d'accueil
       */
      $url = (false == $profil_page_id) ? home_url( "/" ) : get_permalink( (int)$profil_page_id );
      exit( wp_redirect( $url, 301 ) );
    endif;
  }
}, 10, 1);

/** Add shortcode  */
add_shortcode('euromada_login', [ new Euromada_Login(), 'Render' ]);
add_shortcode('euromada_register', [ new Euromada_register(), 'Render' ]);
add_shortcode('euromada_profil', [ new Euromada_profil(), 'Render' ]);

function action_save_postdata( $post_id ) {
  /** for `cost` post meta */
  $valueCost = Services::getValue('cost_recommandation');
  if (false != $valueCost)
    update_post_meta( $post_id, 'cost_recommandation', $valueCost );

  /** for `link` post meta  */
  $valueLink = Services::getValue('link_recommandation');
  if (false != $valueLink)
    update_post_meta( $post_id, 'link_recommandation', $valueLink );
}

add_action( 'save_post', 'action_save_postdata' );

if ( ! function_exists("euromada_setup")):
  function euromada_setup() {
    load_theme_textdomain( 'twentyfifteen' );
    load_theme_textdomain( 'euromada', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );

    /*
    * Enable support for Post Thumbnails on posts and pages.
    */
    add_theme_support( 'post-thumbnails' );
    register_nav_menus( array(
      'top_menu' => 'Top Menu',
      'account'  => 'Account Menu',
      'primary'  => __( 'Primary Menu',      'twentyfifteen' ),
      'social'   => __( 'Social Links Menu', 'twentyfifteen' ),
    ) );
  }
endif;
add_action( 'after_setup_theme', 'euromada_setup' );

/**
 * Register widget area.
 */
function euromada_widgets_init() {
  /** register widget */
  register_widget('search_Widget');

  /** register sidebar */
  register_sidebar( array(
    'name'          => 'Middle Area',
    'id'            => 'middle-area',
    'description'   => 'Add widgets here to appear in middle position.',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="er-widget-title">',
    'after_title'   => '</h2>',
  ) );
  register_sidebar( array(
    'name'          => 'Home slider',
    'id'            => 'home-slider',
    'description'   => 'Add widgets here to appear in home position.',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="er-widget-title">',
    'after_title'   => '</h2>',
  ) );
}
add_action( 'widgets_init', 'euromada_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function euromada_scripts() {
  wp_enqueue_style( 'euromada-style', get_stylesheet_uri() );
  wp_enqueue_script( 'lodash', get_template_directory_uri() . '/js/lodash.min.js', array() );
  wp_enqueue_script( 'uikit', get_template_directory_uri() . '/js/uikit.min.js', array('jquery') );
  wp_enqueue_script( 'uikit-icons', get_template_directory_uri() . '/js/uikit-icons.min.js', array('jquery', 'uikit') );
  wp_enqueue_script( 'moment', get_template_directory_uri() . '/js/moment.min.js', array() );
  wp_enqueue_script( 'vuejs', 'https://cdn.jsdelivr.net/npm/vue', array() );
  wp_enqueue_script( 'vuejs-route', 'https://unpkg.com/vue-router', array() );

  wp_enqueue_script( 'dropdown', get_template_directory_uri() . '/js/dropdown.min.js', array() );
  wp_enqueue_script( 'dimmer', get_template_directory_uri() . '/js/dimmer.min.js', array() );
  wp_enqueue_script( 'rating', get_template_directory_uri() . '/js/rating.min.js', array() );
  wp_enqueue_script( 'transition', get_template_directory_uri() . '/js/transition.min.js', array() );
  wp_enqueue_script( 'form-semantic', get_template_directory_uri() . '/js/form.min.js', array() );
  wp_enqueue_script( 'sidebar-semantic', get_template_directory_uri() . '/js/sidebar.min.js', array() );
  wp_enqueue_script( 'modal-semantic', get_template_directory_uri() . '/js/modal.min.js', array() );

  wp_enqueue_script( 'euromada-script', get_template_directory_uri() . '/scripts-1.0.0.js', array( 'vuejs', 'vuejs-route', 'jquery' ), '20150330', true );
  wp_localize_script( 'euromada-script', 'jParams', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'templateUrl' => get_template_directory_uri(),
    'search' => [
      'mark'     => Services::getTerm('mark'),
      'model'    => Services::getTerm('model'),
      'fuel'     => Services::getTerm('fuel'),
      'category' => Services::getTerm('product_cat')
    ]
  ) );
}
add_action( 'wp_enqueue_scripts', 'euromada_scripts' );

/**
 * For active current menu item.
 */
function uk_active_nav_class( $class, $item ) {
  if (in_array( 'current-menu-item', $class )) {
    $class[] = 'uk-active';
  }
  return $class;
}
add_filter( 'nav_menu_css_class', 'uk_active_nav_class', 10, 2 );


add_action( "wp_head", function(){
  include_once get_template_directory() . '/inc/x-template.php';
}, 10, 2 );


function render_product( $post ) {
  $cost = get_post_meta( $post->ID, 'cost_recommandation', true );
  $link = get_post_meta( $post->ID, 'link_recommandation', true );
  ?>
  <section>
    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="cost">Prix unitaire</label></p>
    <input size="80" type="number" placeholder="Ex: 2000000" id="cost" name="cost_recommandation"  value="<?= $cost ?>" autocomplete="off" required>

    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="link">Lien de l'annonce</label></p>
    <input type="text" placeholder="Ex: http://https://www.leboncoin.fr/outillage_materiaux_2nd_oeuvre/1364621424.htm?ca=7_s" id="link" name="link_recommandation"  value="<?= $link ?>" autocomplete="off" required>
  </section>
  <?php
}