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
  public $gallery = [];

  public $forms = [];

  function __construct() {
    $this->contents = new stdClass();
  }

  public static function createRole() {
    $result = add_role(
      'advertiser',
      'Advertiser',
      array(
          'read'         => true,  // true allows this capability
          'upload_files' => true,
          'edit_posts'   => true,
          'edit_users'   => true,
          'manage_options' => true,
          'remove_users' => true,
          'edit_others_posts'      => true,
          'delete_others_pages'    => true,
          'delete_published_posts' => true,
          'edit_others_posts' => true, // Allows user to edit others posts not just their own
          'create_posts'      => true, // Allows user to create new posts
          'manage_categories' => true, // Allows user to manage post categories
          'publish_posts'   => true, // Allows the user to publish, otherwise posts stays in draft mode
          'edit_themes'     => false, // false denies this capability. User can’t edit your theme
          'install_plugins' => false, // User cant add new plugins
          'update_plugin'   => false, // User can’t update any plugins
          'update_core'     => false // user cant perform core updatesy
      )
    );
    return (null != $result) ? true : false;
  }

  /**
   * Upload media file
   * @param int - int post ID
   * @return void
   */
  public function action_upload_thumbnails( $post_id ) {
    if ( ! is_user_logged_in()) return false;
    if ($_SERVER['REQUEST_METHOD'] != 'POST') return false;

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    if (empty( $_FILES )) return;
    $files = $_FILES[ "images" ];
    foreach ($files[ 'name' ] as $key => $value) {
      if ($files[ 'name' ][ $key ]) {

        /** rename file */
        $filename = $files['name'][$key];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // return file name
        $file_ext = substr($filename, strripos($filename, '.')); // return file extention

        $file = array(
          'name'     => md5($file_basename) . '_' . $post_id . $file_ext,
          'type'     => $files['type'][$key],
          'tmp_name' => $files['tmp_name'][$key],
          'error'    => $files['error'][$key],
          'size'     => $files['size'][$key]
        );
        
        $_FILES = array("upload_file" => $file);
        $attachment_id = media_handle_upload("upload_file", $post_id);
        if (is_wp_error($attachment_id)) {
          /** Error occured */
        } else {
          array_push($this->gallery, $attachment_id);
        }
      }
    }
  }

  public function action_insert_term_product( $args, $post_id ) {
    while (list(, $param) = each($args)) {
      $parent_term = term_exists( $param['value'], $param['taxonomy'] ); // array is returned if taxonomy is given
      $term = wp_insert_term(
        $param['value'], // the term 
        $param['taxonomy'], // the taxonomy
        array(
          'description' => '',
          'parent'      => ''  // get numeric term id
        )
      );

      if (is_wp_error( $term )) {
        if ($term->get_error_code() == 'term_exists') {
          $term_id = (int)$term->get_error_data();
        }
      } else $term_id = &$term;
      wp_set_post_terms( $post_id, $term_id, $param['taxonomy'] );
    }
  }
  /**
   * Action save meta and update
   * @param int - $user_id : User identification
   * @return void
   */
  public function action_euromada_save_meta_user( $user_id ) {
    if ( ! is_int( $user_id )) return false;
    $User = get_user_by('id', $user_id);
    if ( ! $User instanceof WP_User ) return false;
    $adress = Services::getValue('adress');
    $type = Services::getValue('type');
    $phone = Services::getValue('phone');
    update_user_meta($user_id, '_adress_', trim($adress));
    update_user_meta($user_id, '_type_', trim($type));
    update_user_meta($user_id, '_phone_', $phone);
    update_user_meta($user_id, 'show_admin_bar_front', false);
  }

  /**
   * Action update user information
   * @param int - $user_id : User identification
   * @return bool
   */
  public function action_euromada_update_information_user( $user_id ) {
    if ( ! is_user_logged_in())
      return false;

    if ( ! is_int( $user_id )) return false;
    $firstname = Services::getValue('firstname');
    $lastname = Services::getValue('lastname');
    $User = new WP_User( $user_id );
    $update = false;

    if ($User->first_name != $firstname || $User->last_name != $lastname)
      $update = true;
    
    if ( ! $update ) return true;
    $args = [
      'ID' => $user_id,
      'first_name' => $firstname,
      "last_name" => $lastname
    ];
    $result = wp_update_user( $args );
    return  (is_wp_error( $result )) ? false : true;
  }

  /**
   * Action update user password
   * @param int - $user_id : User identification
   * @return bool
   */
  public function action_euromada_update_password( $user_id ) {

  }

  public function insert_advert() {
    if ( ! is_user_logged_in()) return false;
    $User = wp_get_current_user();
    
    /** Insert post */
    $title = Services::getValue('title');
    $content = Services::getValue('description');
    $cost = Services::getValue('cost');

    $postargs = [
      'post_author' => $User->ID,
			'post_title' => esc_html($title),
			'post_content' => apply_filters('the_content', $content),
			'post_status' => 'publish', /* https://codex.wordpress.org/Post_Status */
			'post_parent' => '',
			'post_type' => "product",
    ];

    $post_id = wp_insert_post( $postargs );

    if ( ! is_numeric( $post_id )) 
      return [
        'success' => false,
        'msg' => $post_id->get_error_messages()
      ];
    /** upload files */
    do_action( 'euromada_upload_thumbnails', $post_id );
    update_post_meta($post_id, '_thumbnail_id', empty($this->gallery) ? '' : $this->gallery[ 0 ]);
    /** add meta */
    wp_set_object_terms($post_id, 'simple', 'product_type');

    /* Update post meta, these meta depend a product post_type */
    update_post_meta( $post_id, '_visibility', 'visible');
    update_post_meta( $post_id, '_stock_status', 'instock');
    update_post_meta( $post_id, 'total_sales', '0');
    update_post_meta( $post_id, '_downloadable', 'no');
    update_post_meta( $post_id, '_virtual', 'yes');
    update_post_meta( $post_id, '_regular_price', $cost);
    update_post_meta( $post_id, '_sale_price', '');
    update_post_meta( $post_id, '_purchase_note', '');
    update_post_meta( $post_id, '_featured', 'no');
    update_post_meta( $post_id, '_weight', '');
    update_post_meta( $post_id, '_length', '');
    update_post_meta( $post_id, '_width', '');
    update_post_meta( $post_id, '_height', '');
    update_post_meta( $post_id, '_sku', strtoupper( md5( $post_id )) );
    update_post_meta( $post_id, '_sale_price_dates_from', '');
    update_post_meta( $post_id, '_sale_price_dates_to', '');
    update_post_meta( $post_id, '_price', $cost);
    update_post_meta( $post_id, '_sold_individually', '');
    update_post_meta( $post_id, '_manage_stock', 'no');
    update_post_meta( $post_id, '_backorders', 'no');
    update_post_meta( $post_id, '_stock', '');
    update_post_meta( $post_id, '_product_image_gallery', implode(",", $this->gallery));

    /** add attribut of mileage */
    $mileage = Services::getValue('mileage');
    $term_taxonomy_ids = wp_set_object_terms( get_the_ID(), $mileage, 'pa_mileage', true );
     $data = Array('pa_mileage' => Array(
       'name'        => 'pa_mileage',
       'value'       => $mileage,
       'is_visible'  => 1,
       'is_taxonomy' => 0,
       'is_variation' => 0
     ));
     update_post_meta( $post_id, '_product_attributes', $data); 
    
    $tax_args = [
      [
        'taxonomy' =>  'mark',
        'value' => Services::getValue('mark')
      ],
      [
        'taxonomy' => 'model',
        'value' => Services::getValue('model')
      ],
      [
        'taxonomy' => 'fuel',
        'value' => Services::getValue('fuel')
      ],
      [
        'taxonomy' => 'gearbox',
        'value' => Services::getValue('gearbox')
      ],
      [
        'taxonomy' => 'model-year',
        'value' => Services::getValue('year')
      ]
    ];
    do_action('euromada_insert_term_product', $tax_args, $post_id);
    $url = get_the_permalink( $post_id );
    
    return [
      'success' => true,
      'url' => $url,
      'msg' => "Votre annonce a été publié avec succès. Redirection..."
    ];
  }

  public function update_user() {
    $User = wp_get_current_user();
    /** update user meta */
    do_action("euromada_save_meta_user", $User->ID);
    /** update user */
    do_action("euromada_update_information_user", $User->ID);
  }

  public function register_user() {
    /** Denied access if user is connected */
    if (is_user_logged_in())
      return false;

    $email     = Services::getValue( 'email' );
    $pwd       = Services::getValue( 'pwd' );
    $user_id   = email_exists( $email );
    $lastname  = Services::getValue( 'lastname', '' );
    $firstname = Services::getValue( 'firstname', '' );
    $username  = Services::getValue( 'username' );
    if ( $username == false ) return false;
    if ( $user_id == false ) {
        /* @return id user */
        $args = [
          "user_pass"    => $pwd,
          "user_login"   => $username,
          "user_email"   => $email,
          "display_name" => $lastname,
          "first_name"   => $firstname,
          "last_name"    => $lastname,
          "role"         => "advertiser"
        ];
        $user_id = wp_insert_user( $args );
        
        if ( ! is_wp_error($user_id)){

          /** reset and validate account */
          $user = new WP_User($user_id);
          get_password_reset_key( $user );
          // send_confirmation_mail()

          /* Register success */
          $User = new WP_User( $user_id );
          $User->add_cap('upload_files');
        
          do_action("euromada_save_meta_user", $user_id);
          update_user_meta($user_id, 'show_admin_bar_front', false);

          return [
            'success' => true,
            'msg' => "Inscription success!"
          ];

        } else {
          $errno = &$user_id;
          return  [
            'success' => false,
            'tracking' => 'Create user.',
            'msg' => $errno->get_error_messages()
          ];
        }

    } else {
      return array(
        'success' => false,
        'tracking' => 'Adress `email` or `user` already exists. ',
        'msg' => 'User already exists.'
      );
    }
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
      ],
      [
        "blogname" => "Page profil",
        "id" => "profil_page",
        "page_id" => get_option( "profil_page_id", false ),
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
      
      /** Get only published post */
      $post_status = get_post_status();
      if ($post_status == "private") continue;

      $advert = wc_get_product(get_the_ID());
      $this->full_size_gallery = Services::getThumbnails();
      $this->mainImage = $this->getMainThumbnail( (int)$advert->get_image_id(), [600, 300] );
      array_push( $this->full_size_gallery, $this->mainImage );

      $this->createObjectJS( $advert );
      $this->push();
    endwhile;

    /** return all push products details  */
    return $this->adverts;
    
  }

  /**
   * Get 12 last product lists
   * @param void
   * @return array - (WP_Post) Array of object product details 
   */
  public function getLastAd() {
    $args = array(
      'post_type'      => 'product',
      'post_status'    => 'publish',
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

  /**
   * ****************************************** USER ********************************************
   */

  /**
   * Get product(s)
   * @param void
   * @return array - (WP_Post) Array of object product details 
   */
  public function myAdverts() {
    $products = [];
    /** Denied access if user is connected */
    if ( ! is_user_logged_in())
      return false;
    $user = wp_get_current_user();
    $args = [
      'post_type' => 'product',
      'author' => $user->ID,
      'posts_per_page' => -1
    ];
    $query = new WP_Query( $args );
    if ($query->have_posts()) {
      while ($query->have_posts()) : $query->the_post();
        $this->contents = new stdClass();
        $advert = wc_get_product($query->post->ID);
        $this->mainImage = $this->getMainThumbnail( (int)$advert->get_image_id(), [200, 50] );

        $this->createObjectJS( $advert );
        $this->push();
      endwhile;
    }
    wp_reset_postdata();
    return $this->adverts;
  }

}
