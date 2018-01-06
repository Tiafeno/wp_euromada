<?php
require get_template_directory() . '/inc/class-euromada.php';
require get_template_directory() . '/inc/class-services.php';
require get_template_directory() . '/inc/class-walker.php';
/** Widget */
require get_template_directory() . '/inc/widgets/search.widget.php';

function euromada_init() {
  Euromada::taxonomy();
  Euromada::setRecommandation();
}
add_action( 'init', 'euromada_init');

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

if (! function_exists("euromada_setup")):
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

  wp_enqueue_script( 'euromada-script', get_template_directory_uri() . '/app.js', array( 'vuejs', 'vuejs-route', 'jquery' ), '20150330', true );
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
  include get_template_directory() . '/inc/x-template.php';
}, 10, 2 );


add_action( 'admin_menu', function () {
  add_meta_box( 'products', 'Produits', "render_product", "recommandation", 'normal', 'low' );
} );

function render_product( $post ) {
  $cost = get_post_meta( $post->ID, 'cost_recommandation', true );
  $link = get_post_meta( $post->ID, 'link_recommandation', true );
  ?>
  <section>
    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="cost">Prix unitaire</label></p>
    <input size="80" type="number" placeholder="Ex: 2000000" id="cost" name="cost_recommandation"  value="<?= $cost ?>" autocomplete="off">

    <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="link">Lien de l'annonce</label></p>
    <input type="text" placeholder="Ex: http://https://www.leboncoin.fr/outillage_materiaux_2nd_oeuvre/1364621424.htm?ca=7_s" id="link" name="link_recommandation"  value="<?= $link ?>" autocomplete="off">
  </section>
  <?php
}