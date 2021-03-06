<?php
/**
 * Author: Tiafeno Finel
 * Organisation: Entreprise FALI (Falicrea)
 * Author mail: tiafenofnel@gmail.com
 */

date_default_timezone_set( 'UTC' );
@setlocale( LC_TIME, "fra_fra" );
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
define('__site_key__', "6LdkSUkUAAAAAMqVJODAt7TpAMUX9LJVVnOlz9gX"); /** Google api key */

$search_page_id = get_option( "search_page_id", false );
$search_url = (false === $search_page_id) ? site_url('/') : get_permalink( (int)$search_page_id );
/**
 *
 */
define('__SEARCH_URL__', $search_url);

$MESSAGE = null;
/** Les Etats qui sont autorisé a publier des annonces */
define('STATES', serialize([
  "Allemagne",
  "France",
  "Suisse",
  "Espagne",
  "Belgique",
  "Pologne"
]));

/** Class dependence */
require get_template_directory() . '/inc/class-euromada.php';
require get_template_directory() . '/inc/class-services.php';
require get_template_directory() . '/inc/class-walker.php';
require get_template_directory() . '/inc/class-order.php';
require get_template_directory() . '/inc/class-message.php';

/** Class shortcode */
require get_template_directory() . '/inc/shortcode/shortcode.php';
require get_template_directory() . '/inc/shortcode/class-login.php';
require get_template_directory() . '/inc/shortcode/class-register.php';
require get_template_directory() . '/inc/shortcode/class-update.php';
require get_template_directory() . '/inc/shortcode/class-profil.php';
require get_template_directory() . '/inc/shortcode/class-publisher.php';
require get_template_directory() . '/inc/shortcode/class-embed.php';

/** Widget */
require get_template_directory() . '/inc/widgets/search.widget.php';

$instanceEuromada = new Euromada();
$updateInstance = new Euromada_update();

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

/** Action wordpress personnalisé */
add_action("euromada_save_meta_user", [ $instanceEuromada, 'action_euromada_save_meta_user' ], 10, 1);
add_action("euromada_update_information_user", [ $instanceEuromada, 'action_euromada_update_information_user' ], 10, 1);
add_action("euromada_upload_thumbnails", [ $instanceEuromada, 'action_upload_thumbnails' ], 10, 1);
add_action("euromada_insert_term_product", [ $instanceEuromada, 'action_insert_term_product' ], 10, 2);

add_action("euromada_init_object_product", [ $instanceEuromada, 'action_init_object_product' ], 10, 0);

/** Seule les administrateur peuvent voir le menu administrateur */
add_action( 'after_setup_theme', function() {
  if ( ! current_user_can( 'administrator' ) && ! is_admin() ) { show_admin_bar( false ); }
});

/**
 * Changer les roles des indentifiants qui s'inscrit
 * dans la formulaire de commande de woocommerce.
 */
add_action("woocommerce_created_customer", function ($user_id) {
  $currentUser = new WP_User($user_id);
  $currentUser->set_role("advertiser");
}, 10, 3);

 /** On load wordpress */
add_action( "wp_loaded", function () {
  global $MESSAGE, $instanceEuromada;

	$orders = Services::getSession( 'orders', false );
	if ( ! $orders ) {
		$_SESSION['orders'] = Array();
	}
  /**
   * **************************
   *   Mise à jours du profil
   * **************************
   */
  if (isset($_POST[ 'edit_profil_nonce' ]) &&
  wp_verify_nonce($_POST[ 'edit_profil_nonce' ], 'edit_profil') && is_user_logged_in()) {
    $instanceEuromada->update_user();
  }

  /**
   * ***************************
   *     Publier une annonce
   * ***************************
   */
  if (isset($_POST[ 'publish_nonce' ]) && wp_verify_nonce($_POST[ 'publish_nonce' ], 'publish') && is_user_logged_in()) {
    /** 
     * *************************************
     *  Verification du titre de l'annonce 
     * *************************************
     */
    $findArray = [ 'Vente', 'Achat' ];
    $title = Services::getValue('euromada_title', false);
    if (false != $title)
      while (list(, $find) = each($findArray)) {
        if (preg_match("/\b" . $find . '\b/i', $title)) {
          $MESSAGE = new EM_Message('Veillez verifier le titre de votre annonce', '', 'negative');
        }
      }
    unset( $title );

    if ( ! $MESSAGE instanceof EM_Message):
      $insertResult = $instanceEuromada->insert_advert();
      if ($insertResult[ 'success' ]) {
        exit( wp_redirect( $insertResult[ 'url' ], 301 ) );
      } else {
        $MESSAGE = new EM_Message($insertResult[ 'msg' ], '', 'negative');
      }
    endif;
  }
  
  /**
   * ****************************************
   * Mise à jours des paramètres d'EUROMADA
   * @privilège - Administrateur
   * ****************************************
   */
  if (isset($_POST[ 'euromada_settings_nonce' ]) &&
  wp_verify_nonce($_POST[ 'euromada_settings_nonce' ], 'euromada_settings') &&
  is_admin() ) {

    $options = [ 
      'register_page', 
      'login_page',
      'offres_page', 
      'profil_page',
      'edit_page',
      'search_page'
    ];

    while (list(, $option) = each($options)):
      $inputValue = Services::getValue($option, '');
      update_option( $option . '_id', $inputValue );
    endwhile;
    
    $inputs = [ "max_price", "min_price" ];
    foreach ($inputs as $input ) {
      $inputValue = Services::getValue( $input );
      update_option( $input, (int)$inputValue );
    }

  }

  /**
   * ***************************************************************
   *   Enregistrer une nouvelle utilisateur dans la base de donnée
   * ***************************************************************
   */
  if (isset($_POST[ 'register_nonce' ]) &&
  wp_verify_nonce($_POST[ 'register_nonce' ], 'register') ) {
    $results = $instanceEuromada->register_user();
    $singin = (object)$results;
    $type =  ((bool)$singin->success == false) ? 'negative' : 'positive';
    $MESSAGE = new EM_Message($singin->msg, 'Inscription', $type);
  }

});

/** On login fail */
add_action( 'wp_login_failed', function() {
  $referer = $_SERVER[ 'HTTP_REFERER' ];
  // if there's a valid referrer, and it's not the default log-in screen
  if ( !empty($referer) && !strstr($referer, 'wp-login') && !strstr($referer, 'wp-admin') ) {
    exit(wp_redirect( $referer . '?login=failed', 302 ));  // let's append some information (login=failed) to the URL for the theme to use
  }
});

add_action( 'admin_menu', function() {
  add_meta_box( 'products', 'Produits', "render_product", "recommandation", 'normal', 'low' );
  add_menu_page('euromada', 'Euromada', 'manage_options', 'euromada', 
    array(new Euromada, 'euromada_admin_template'), 'dashicons-admin-settings');
});

/**
 * Init admin page
 */
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

/**
 * Action delete advert with ajax
 *
 * @param {void}
 * @return {json}
 */
function ajx_action_delete_advert() {
  $profil_page_id = get_option( 'profil_page_id', false );
  $profil_url = (false == $profil_page_id) ? home_url( "/" ) : get_permalink( (int)$profil_page_id );

  // Envoyer l'annonce dans la corbeille 
  $post_delete_id = Services::getValue('__post_delete_id', false);
  $delete_post = null;
  if (false != $post_delete_id) :
    $delete_post = Euromada_profil::trash_post( $post_delete_id );
    if ( false != $delete_post || is_null($delete_post) ) :
      wp_send_json( [ 'success' => true, 'response' => $delete_post ] );
    else:
      wp_send_json( [ 'success' => $delete_post, 'response' => 'Une erreur c\'est produit' ] );
    endif;
  endif;
}

function ajx_action_order_review() {
	/**
	 * @func wp_doing_ajax
	 * (bool) True if it's a WordPress Ajax request, false otherwise.
	 */

	if ( ! wp_doing_ajax() ) {
		return;
	}
	$session_orders = Services::getSession( 'orders', false );
	$post_order     = Services::getValue( 'order' );
	if ( false == $post_order ) {
		wp_send_json( [ "success" => false, "msg" => "Post order is empty" ] );
	}
	$orders = explode( '_', $post_order );

	$_SESSION['orders']            = [];
	$_SESSION['orders']['orderby'] = $orders[0];
	$_SESSION['orders']['order']   = strtoupper( $orders[1] );
	wp_send_json( [
		"success" => true,
		"msg"     => Services::getSession( 'orders' )
	] );
}

add_action( 'init', function () {

	if ( ! session_id() )
		session_start();

	add_action('wp_ajax_ajx_action_delete_advert', 'ajx_action_delete_advert');
	add_action('wp_ajax_nopriv_ajx_action_delete_advert', 'ajx_action_delete_advert' );

	add_action( 'wp_ajax_ajx_action_order_review', 'ajx_action_order_review' );
	add_action( 'wp_ajax_nopriv_ajx_action_order_review', 'ajx_action_order_review');

	Euromada::taxonomy();
	Euromada::setRecommandation();
}, 1);

/**
 * Redirect in home page if user is login
 */
add_action( 'get_header', function () {
	global $post, $instanceEuromada;
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

  $profil_page_id = get_option( 'profil_page_id', false );
  $profil_url = (false == $profil_page_id) ? home_url( "/" ) : get_permalink( (int)$profil_page_id );

  /**
   * Mettre à jours l'annonce
   */
  if (isset($_POST[ 'update_nonce' ]) && wp_verify_nonce($_POST[ 'update_nonce' ], 'update') ) 
  {
    $post_update_id = Services::getValue('post_id', false);
    if (false != $post_update_id) $instanceEuromada->update_advert( $post_update_id );
  }


  /** 
   * Vérifier l'en-tete de la page
   */
  if (is_user_logged_in()) {
    if ($post == null) return;
    $login_page_id = get_option( 'login_page_id', false );
    if (is_int( (int)$login_page_id ) ) :
      /**
       * On verifie si la page actuel n'est pas une page pour se connecter.
       * Si non, on reste dans cette page.
       */
      if ($post->ID != (int)$login_page_id) return true;
      exit( wp_redirect( $profil_url, 301 ) );
    endif;
  }
}, 10, 1);

/** Add shortcode  */
add_shortcode('euromada_login', [ new Euromada_Login(), 'Render' ]);
add_shortcode('euromada_register', [ new Euromada_register(), 'Render' ]);
add_shortcode('euromada_profil', [ new Euromada_profil(), 'Render' ]);
add_shortcode('euromada_publisher', [ new Euromada_publisher(), 'Render' ]);
add_shortcode('euromada_embed', [ new Euromada_embed(), 'Render' ]);
add_shortcode('euromada_update', [ new Euromada_update(), 'Render' ]);

function action_save_postdata( $post_id ) {

  /**
   * **********************************************
   * Update recommandation post type custom input
   * **********************************************
   */
  $inputValues = [
    'cost_recommandation',
    'link_recommandation'
  ];
  foreach ($inputValues as $inputValue) {
    $value = Services::getValue( $inputValue );
    if (false != $value)
      update_post_meta( $post_id, $inputValue, $value );
  }

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
  register_sidebar( array(
    'name'          => 'Bannier Top',
    'id'            => 'bannier-top',
    'description'   => 'Add widgets here to appear in top position.',
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
  // wp_enqueue_style( 'euromada-style', get_stylesheet_uri() );
  wp_enqueue_script( 'bluebird', get_template_directory_uri() . '/js/bluebird.min.js', array('jquery') );
  wp_enqueue_script( 'lodash', get_template_directory_uri() . '/js/lodash.min.js', array('jquery') );
  wp_enqueue_script( 'uikit', get_template_directory_uri() . '/js/uikit.min.js', array('jquery') );
  wp_enqueue_script( 'venobox', get_template_directory_uri() . '/js/venobox/venobox.min.js', array('jquery') );
  wp_enqueue_script( 'uikit-icons', get_template_directory_uri() . '/js/uikit-icons.min.js', array('jquery', 'uikit') );
  wp_enqueue_script( 'moment', get_template_directory_uri() . '/js/moment.min.js', array() );
  wp_enqueue_script( 'vuejs', 'https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js', array() );
	// wp_enqueue_script( 'vuejs-route', 'https://unpkg.com/vue-router', array() );

	/** Debugger */
	// wp_enqueue_script( 'vuejs-inspector', 'https://cdn.jsdelivr.net/npm/vue-inspector@0.4.3/dist/js/vue-inspector.min.js', ['vuejs']);

  wp_enqueue_script( 'dropdown', get_template_directory_uri() . '/js/dropdown.min.js', array() );
  wp_enqueue_script( 'dimmer', get_template_directory_uri() . '/js/dimmer.min.js', array() );
  wp_enqueue_script( 'rating', get_template_directory_uri() . '/js/rating.min.js', array() );
  wp_enqueue_script( 'transition', get_template_directory_uri() . '/js/transition.min.js', array() );
  wp_enqueue_script( 'form-semantic', get_template_directory_uri() . '/js/form.min.js', array() );
  wp_enqueue_script( 'sidebar-semantic', get_template_directory_uri() . '/js/sidebar.min.js', array() );
  wp_enqueue_script( 'modal-semantic', get_template_directory_uri() . '/js/modal.min.js', array() );

	wp_enqueue_script( 'euromada-script', get_template_directory_uri() . '/scripts-1.0.1.js', array(
		'vuejs',
		'jquery'
	), '13042018-05', true );
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

/**
 * Vuejs template
 */
function _getiFramePage( $url = '#' ) {
  $iFramePageId = get_option( 'iframe_page_id', false );
  if (false != $iFramePageId) {
    $iFramePageUrl = get_the_permalink( $iFramePageId );
    return $iFramePageUrl . '?ref_url=' . $url;
  } else return $url;

}
add_action( "wp_head", function(){
  include_once get_template_directory() . '/inc/x-template.php';
}, 10, 2 );

/** SEO */
add_action('wp_head', function() {
  global $post;
  if ($post == null) return;
  $thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
  $image        = wp_get_attachment_url( (int)$thumbnail_id );
  $current_url  = get_the_permalink(get_the_ID() );
	$type         = ( $post->post_type == "product" ) ? "product" : "article";
?>
  <meta property="og:locale" content="fr_FR"/>
    <meta property="og:type" content="<?= $type ?>" />
  <meta property="og:title" content="<?= get_the_title() ?>" />
  <meta property="og:description" content="<?= sanitize_textarea_field( $post->post_content ) ?>" />
  <meta property="og:url" content="<?= $current_url ?>" />
  <meta property="og:site_name" content="EUROMADA" />
  <meta property="og:image" content="<?= $image ?>" />
	<?php
	if ( $type == "product" ) :
		$product = wc_get_product( $post->ID );
		$terms      = get_the_terms( $product->get_id(), 'product_cat' );
		foreach ( $terms as $term ):
			$product_cat_name = $term->name;
			break;
		endforeach;
		$catName = isset( $product_cat_name ) ? $product_cat_name : "";
		?>
      <meta property="product:category" content="<?= $catName ?>"/>
      <meta property="product:price:amount" content="<?= $product->get_price() ?>"/>
      <meta property="product:price:currency" content="EUR"/>
      <meta property="product:shipping_cost:amount" content="<?= $product->get_price() ?>"/>
      <meta property="product:shipping_cost:currency" content="EUR"/>
      <meta property="product:sale_price:amount" content="<?= $product->get_price() ?>"/>
      <meta property="product:sale_price:currency" content="EUR"/>
	<?php
	endif;
}, 10, 2);

add_action( "admin_head", function(){
  wp_enqueue_script( 'dropdown', get_template_directory_uri() . '/js/dropdown.min.js', array() );
  wp_enqueue_script( 'transition', get_template_directory_uri() . '/js/transition.min.js', array() );
  wp_enqueue_script( 'form-semantic', get_template_directory_uri() . '/js/form.min.js', array() );
?>
  <style type="text/css">
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/semantic.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/dropdown.css");
    @import url("<?php echo esc_url( get_template_directory_uri() ); ?>/css/transition.css");
  </style>
  <script type="text/javascript">
    (function($){
      $(document).ready(function() {
        $('.ui.dropdown').dropdown();
      });
    })(jQuery)
  </script>
<?php
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