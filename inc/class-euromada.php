<?php
/**
 * Author: Tiafeno Finel
 * Organisation: Entreprise FALI (Falicrea)
 * Author mail: tiafenofnel@gmail.com
 */


class Euromada {
  public static $taxonomies = [ "Mark", "Model", "Model Year", "Fuel", "GearBox" ];

  public $full_size_gallery = [];
  public $thumbnail_gallery = [];
  public $adverts = []; // return value
  public $mainImage;
  public $contents;

  public $forms = [];

  function __construct() {
    $this->contents = new stdClass();
  }

  /**
   * This function add input or select form in euromada page admin
   * @param void
   * @return object $this - This class instance
   */
  public function getForms() {
    $this->forms = [
      [
        "blogname" => "Page pour login",
        "id" => "login_page",
        "page_id" => get_option( "login_page_id", false ),
        "description" => ""
      ],
      [
        "blogname" => "Page pour s'enregistrer",
        "id" => "register_page",
        "page_id" => get_option( "register_page_id", false ),
        "description" => ""
      ]
    ];
    return $this;
  }

  /**
   * This function render euromada admin template menu
   * @param void
   * @return void
   */
  public function euromada_admin_template() {
    $params = [
      'post_type' => 'page',
      'posts_per_page' => -1
    ];
    $posts = get_posts( $params );
    $forms = $this->getForms()->forms;

    include_once get_template_directory() . '/inc/tpls/admin.tmpl.php';
  }

  /**
   * Create dependancy taxonomies for this template
   * @return void
   */
  public static function taxonomy() {
    $post_type = "product";
    if (post_type_exists( $post_type )) :
      for($pos = 0; $pos < count(self::$taxonomies); $pos++) {
        register_taxonomy(
          sanitize_title( self::$taxonomies[ $pos ] ),
          $post_type,
          array(
            'label' => __(self::$taxonomies[ $pos ], 'euromada'),
            'rewrite' => array( 'slug' => sanitize_title( self::$taxonomies[ $pos ] ) ),
            'hierarchical' => true,
            'show_ui' => true
          )
        );
      }
    endif;
  }

  /** 
   * Create custom post type `recommandation`  - call in function.php file
   * @param void
   * @return void
   */
  public static function setRecommandation() {
    register_post_type( "recommandation", array(
      'label'         => _x( "Recommandation", 'General name for "recommandation" post type' ),
      'labels'        => array(
        'name'               => _x( "Recommandations", "Plural name for Recommandation post type" ),
        'singular_name'      => _x( "Recommandation", "Singular name for Recommandation post type" ),
        'add_new'            => __( 'Ajouter' ),
        'add_new_item'       => __( "Ajouter une recommandation" ),
        'edit_item'          => __( 'Modifier' ),
        'view_item'          => __( 'Voir' ),
        'search_items'       => __( "Rechercher une recommandation" )
      ),
      'public'        => true,
      'hierarchical'  => false,
      'menu_position' => 100,
      'menu_icon'     => "dashicons-book",
      'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt' ]
    ) );

  }

  /**
   * Push the dependancy content in global array variable.
   * @param void
   * @return void
   */
  protected function push() {
    array_push( $this->adverts, $this->contents );
  }

  /**
   * Add the dependancy in contents object
   * @param object $advert - Woocommerce product class
   * @return void
   */
  protected function createObjectJS( &$advert ) {
    $this->contents->id = $advert->get_id();
    $this->contents->title = $advert->get_title();
    $this->contents->cost = $advert->get_price();
    $this->contents->countPic = count( $this->full_size_gallery );
    $this->contents->description = $advert->get_description();
    $this->contents->dateadd = $advert->get_date_created();
    $this->contents->imgLink = $this->mainImage[0][0];
    $this->contents->url = get_the_permalink( $advert->get_id() );
    $this->contents->attributes = Services::getObjectTerms();
  }

  /**
   * Get main product thumbnail 
   * @param int $id - This is product or post ID
   * @param string|array $size
   * @return array
   */
  protected function getMainThumbnail( $id, $size = "full" ) {
    $image   = wp_get_attachment_image_src( $id, $size );
    $title   = get_post_field( 'post_title', $id );
    $excerpt = get_post_field( 'post_excerpt', $id );
    return [ $image, $title, $excerpt ];
  }

  /**
   * Get all lists of products (use by content-archive-product)
   * Change pagination Option -> Reading in wordpress.
   * @param void
   * @return array - Array of object product details
   */
  public function getAdverts() {
    while (have_posts()) : the_post();
      $this->contents = new stdClass();
      $advert = wc_get_product(get_the_ID());
      $this->full_size_gallery = Services::getThumbnails();
      $this->mainImage = $this->getMainThumbnail( (int)$advert->get_image_id(), [600, 300] );
      array_push( $this->full_size_gallery, $this->mainImage );

      $this->createObjectJS( $advert );
      $this->push();
    endwhile;

    /** return all products details pushed  */
    return $this->adverts;
    
  }

  public function getLastAd() {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => 12
    );
    query_posts($args); 
    if (have_posts()) {
      while (have_posts()) : the_post();
        $this->contents = new stdClass();
        $advert = wc_get_product(get_the_ID());
        $this->full_size_gallery = Services::getThumbnails();
        $this->mainImage = $this->getMainThumbnail( (int)$advert->get_image_id(), [200, 50] );
        array_push( $this->full_size_gallery, $this->mainImage );

        $this->createObjectJS( $advert );
        $this->push();
      endwhile;
    }
    wp_reset_query();
    return $this->adverts;
  }

  /**
   * Get single product details
   * @param void
   * @return object
   */
  public function getAdvert() {
    $advert = wc_get_product(get_the_ID());
    $this->full_size_gallery = Services::getThumbnails();
    $this->thumbnail_gallery = Services::getThumbnails( [100, 100] );

    $this->mainImage = $this->getMainThumbnail( (int)$advert->get_image_id(), "full" );
    array_push( $this->full_size_gallery, $this->mainImage );

    $thumbnail_main = $this->getMainThumbnail( (int)$advert->get_image_id(), [100, 100]);
    array_push( $this->thumbnail_gallery, $thumbnail_main );

    $this->createObjectJS( $advert );

    $gallery = new stdClass();
    $gallery->full = $this->full_size_gallery;
    $gallery->thumbnail = $this->thumbnail_gallery;
    $this->push();
    $this->contents->gallery = $gallery;
    return $this->contents;
  }
}
