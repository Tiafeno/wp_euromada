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
  public $no_image_parms = [
    ['http://api.falicrea.com/thumbnails/cover.png', 1280, 960]
  ];

  public $forms = [];
  public $tax_args = [];

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
    if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) return false;

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    if ( empty( $_FILES ) ) return;
    $files = $_FILES[ "euromada_images" ];
    foreach ($files[ 'name' ] as $key => $value) {
      if ($files[ 'name' ][ $key ]) {

        /** rename file */
        $filename = $files['name'][$key];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // return file name
        $file_ext = substr($filename, strripos($filename, '.')); // return file extention

        $file = array(
          'name'     => md5($file_basename) . '_' . $post_id . $file_ext,
          'type'     => $files[ 'type' ][ $key ],
          'tmp_name' => $files[ 'tmp_name' ][ $key ],
          'error'    => $files[ 'error' ][ $key ],
          'size'     => $files[ 'size' ][ $key ]
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
    $type   = Services::getValue('type');
    $phone  = Services::getValue('phone');

    update_user_meta($user_id, '_adress_', trim($adress));
    update_user_meta($user_id, '_type_', trim($type));
    update_user_meta($user_id, '_phone_', $phone);

    if ( ! current_user_can( 'administrator' ) && ! is_admin() ) 
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

  public function action_insert_term_product( $args, $post_id ) {
    while (list(, $param) = each($args)) {
      if ($param[ 'value' ] == false) continue;
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

  public function action_init_object_product() {
    $this->tax_args = [
      [
        'taxonomy' =>  'mark',
        'value' => Services::getValue('euromada_mark')
      ],
      [
        'taxonomy' => 'model',
        'value' => Services::getValue('euromada_model')
      ],
      [
        'taxonomy' => 'fuel',
        'value' => Services::getValue('euromada_fuel')
      ],
      [
        'taxonomy' => 'gearbox',
        'value' => Services::getValue('euromada_gearbox')
      ],
      [
        'taxonomy' => 'model-year',
        'value' => Services::getValue('euromada_year')
      ],
      [
        'taxonomy' => 'product_cat',
        'value' => Services::getValue('euromada_category')
      ]
    ];
  }

  private function delete_all_post_relationships( $post_id ) {
    foreach ($this->tax_args as $args)
      wp_delete_object_term_relationships( $post_id, $args[ 'taxonomy' ] );
    return $this;
  }

  public function update_product_terms( $post_id ) {
    do_action('euromada_insert_term_product', $this->tax_args, $post_id);
  }

  /**
   * Mettre à jours une annonce
   * @param {int} $post_id 
   * @return {void}
   */
  public function update_advert( $post_id ) {
    if (wp_doing_ajax() || ! is_user_logged_in() || ! $post_id) 
      return;

    $this->action_init_object_product();
    // Get post
    $pst = get_post( $post_id );
    if (is_null($pst) || ! $pst instanceof WP_Post) {
      echo 'Warning: L\'annonce n\'existe pas dans la base de donnée';
      return;
    }
    $User = wp_get_current_user();
    if ($pst->post_author != $User->ID) {
      echo 'Warning: Impossible de mettre à jours cette annonce';
      return;
    }
    $title = Services::getValue('euromada_title', $pst->post_title);
    $content = Services::getValue('euromada_description', $pst->post_content);
    $args = [
      'ID' => $pst->ID,
      'post_title' => $title,
      'post_content' => $content
    ];
    $update_results = wp_update_post( $args, false );
    if ( ! is_wp_error($update_results)) {
      /** Update post product meta and localisation */
      $price = Services::getValue('euromada_cost', 0);
      update_post_meta( $pst->ID, '_regular_price', $price);
      update_post_meta( $pst->ID, '_price', $price);

      update_post_meta( $pst->ID, '_state', Services::getValue('euromada_state', '') );
      update_post_meta( $pst->ID, '_postalcode', Services::getValue('euromada_postal_code', '') );
      update_post_meta( $pst->ID, '_adress', Services::getValue('euromada_adress', '') );

      /** Update product attributes */
      $mileage = Services::getValue('euromada_mileage', '');
      $data = Array('pa_mileage' => Array(
        'name'        => 'pa_mileage',
        'value'       => $mileage,
        'is_visible'  => 1,
        'is_taxonomy' => 0,
        'is_variation' => 0
      ));
      update_post_meta( $pst->ID, '_product_attributes', $data); 

      /** Update taxonomy */
      $this
        ->delete_all_post_relationships($pst->ID)
        ->update_product_terms( $pst->ID );
      echo 'Update without error';
    } else {
      echo $update_results->get_error_messages();
    }

  }

  /**
   * Ajouter une annonce dans le site
   * @param void
   * @return array
   */
  public function insert_advert() {
    // Validation d'utilisateur et l'autorisation requis
    if ( ! is_user_logged_in()) return false;
    $User = wp_get_current_user();

    $this->action_init_object_product();
    /** Insert post */
    $title   = Services::getValue('euromada_title');
    $content = Services::getValue('euromada_description');
    $cost    = Services::getValue('euromada_cost');

    $postargs = [
      'post_author'  => $User->ID,
			'post_title'   => esc_html($title),
			'post_content' => apply_filters('the_content', $content),
			'post_status'  => 'publish', /* https://codex.wordpress.org/Post_Status */
			'post_parent'  => '',
			'post_type'    => "product",
    ];
    $post_id = wp_insert_post( $postargs );

    unset( $User );

    /** 
     * Vérifier le status de l'insertion de l'annonce s'il n'y a pas d'erreur.
     */
    if ( ! is_numeric( $post_id )) 
      return [
        'success' => false,
        'msg' => $post_id->get_error_messages()
      ];

    /** 
     * *******************************************************
     * Envoyer les photos et joindre ces images dans l'annonce
     * ****************************************************** 
     */
    do_action( 'euromada_upload_thumbnails', $post_id );
    update_post_meta($post_id, '_thumbnail_id', empty($this->gallery) ? '' : $this->gallery[ 0 ]);

    /** 
     * Ajouter une type du produit (simple produit)
     */
    wp_set_object_terms($post_id, 'simple', 'product_type');

    /**
     * **************************************
     *  Update post product meta dependency
     * *************************************
     */
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

    /**
     * ***************************
     * Localisation de l'annonce
     * ***************************
     * 
     */
    update_post_meta( $post_id, '_state', Services::getValue('euromada_state', '') );
    update_post_meta( $post_id, '_postalcode', Services::getValue('euromada_postal_code', '') );
    update_post_meta( $post_id, '_adress', Services::getValue('euromada_adress', '') );

    /** 
     * ****************************************************************
     * Ajouter une attribut à la produit (pa_mileage) - Le kilometrage
     * ***************************************************************
     */
    $mileage = Services::getValue('euromada_mileage');
    $term_taxonomy_ids = wp_set_object_terms( get_the_ID(), $mileage, 'pa_mileage', true );
     $data = Array('pa_mileage' => Array(
       'name'        => 'pa_mileage',
       'value'       => $mileage,
       'is_visible'  => 1,
       'is_taxonomy' => 0,
       'is_variation' => 0
     ));
     update_post_meta( $post_id, '_product_attributes', $data); 
    
     /**
      * ********************************
      * Ajout des terms dans le produit
      **********************************
      */
    $this->update_product_terms( $post_id );
    
    return [
      'success' => true,
      'url' => get_the_permalink( $post_id ),
      'msg' => "Votre annonce a été publié avec succès. Redirection..."
    ];
  }

  /**
   * Mise à jour des information du l'utilisateur
   * @param void
   * @return void
   */
  public function update_user() {
    $User = wp_get_current_user();
    /** update user meta */
    do_action("euromada_save_meta_user", $User->ID);

    /** 
     * Mise à jour des champs suivants:
     *  - firstname
     *  - lastname
     */
    do_action("euromada_update_information_user", $User->ID);
  }

  /**
   * This function register an user in bdd
   * @param void
   * @return array - 
   */
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

          $adress = Services::getValue('adress');
          update_user_meta( $user_id, "billing_first_name", $firstname );
          update_user_meta( $user_id, "billing_last_name", $lastname );
          update_user_meta( $user_id, "billing_address_1", $adress );
          update_user_meta( $user_id, "billing_phone", Services::getValue('phone') );

          update_user_meta( $user_id, "shipping_first_name", $firstname );
          update_user_meta( $user_id, "shipping_last_name", $lastname );
          update_user_meta( $user_id, "shipping_address_1", $adress );

          update_user_meta($user_id, 'show_admin_bar_front', false);

          return [
            'success' => true,
            'msg' => "Votre inscription a réussi."
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
        "name" => "login_page",
        "type" => "select",
        "page_id" => get_option( "login_page_id", false ),
        "description" => ""
      ],
      [
        "blogname" => "Page offres",
        "name" => "offres_page",
        "type" => "select",
        "page_id" => get_option( "offres_page_id", false ),
        "description" => ""
      ],
      [
        "blogname" => "Page pour s'enregistrer",
        "name" => "register_page",
        "type" => "select",
        "page_id" => get_option( "register_page_id", false ),
        "description" => ""
      ],
      [
        "blogname" => "Page profil",
        "name" => "profil_page",
        "type" => "select",
        "page_id" => get_option( "profil_page_id", false ),
        "description" => ""
      ],
      [
        "blogname" => "Page d'edition",
        "name" => "edit_page",
        "type" => "select",
        "page_id" => get_option( "edit_page_id", false ),
        "description" => "Page pour modifier une annonce"
      ],
      [
        "blogname" => "Page search",
        "name" => "search_page",
        "type" => "select",
        "page_id" => get_option( "search_page_id", false ),
        "description" => ""
      ],
      [
        "blogname" => "Prix maximum",
        "name" => "max_price",
        "type" => "input",
        "value" => get_option( "max_price", false ),
        "description" => ""
      ],
      [
        "blogname" => "Prix minimum",
        "name" => "min_price",
        "type" => "input",
        "value" => get_option( "min_price", false ),
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

    /**
     * Location
     */
    $location = Services::getLocation( $advert->get_id() );
    array_push($this->contents->attributes, [
      'name' => $location,
      'taxonomy' => "VilleChaingy"
    ]);
    /**
     * Get attributs mileage
     */
    $mileage = $advert->get_attribute( 'pa_mileage' );
    array_push($this->contents->attributes, [ 
      'name' => $mileage . ' KM',
      'taxonomy' => 'mileage'
    ]);
  }

  /**
   * Get main product thumbnail 
   * @param int $id - This is product or post ID
   * @param string|array $size
   * @return array|false
   */
  protected function getMainThumbnail( $id, $size = "full" ) {
    $image   = wp_get_attachment_image_src( $id, $size );
    if (false == $image) return false;
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
  public function getAdverts( $args ) {
    $response = new stdClass();
    $postsQueried = new WP_Query( $args );
    $count = $postsQueried->post_count;
    
    while ($postsQueried->have_posts()) : $postsQueried->the_post();

      $this->contents = new stdClass();
      
      /** Get only published post */
      $post_status = get_post_status();
      if ($post_status == "private") continue;

      $advert = wc_get_product($postsQueried->post->ID);
      $this->full_size_gallery = Services::getThumbnails("full", [], $postsQueried->post->ID);
      $Image = $this->getMainThumbnail( (int)$advert->get_image_id(), [600, 300] );
      $this->mainImage = $Image == false ? $this->no_image_parms : $Image;
      // if ($this->mainImage != false)
      //  array_push( $this->full_size_gallery, $this->mainImage );

      $this->createObjectJS( $advert );
      $this->push();
    endwhile;
    $response->postQueried = &$postsQueried;
    $response->adverts = $this->adverts;
    wp_reset_postdata();
    /** return all push products details  */
    return $response;
    
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
        $Image = $this->getMainThumbnail( (int)$advert->get_image_id(), [100, 50] );
        $this->mainImage = $Image == false ? $this->no_image_parms : $Image;
        if ($this->mainImage != false)
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

    // $image = $this->getMainThumbnail( (int)$advert->get_image_id(), "full" );
    // $this->mainImage = $image == false ? $this->no_image_parms : $image;
    // if ($image != false)
    //   array_push( $this->full_size_gallery, $image );

    // $thumbnail_main = $this->getMainThumbnail( (int)$advert->get_image_id(), [100, 100]);
    // if ($thumbnail_main != false)
    //   array_push( $this->thumbnail_gallery, $thumbnail_main );

    $this->createObjectJS( $advert );

    $gallery = new stdClass();
    $gallery->full = $this->full_size_gallery;
    $gallery->thumbnail = $this->thumbnail_gallery;
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
        $main_thumbnail = $this->getMainThumbnail( (int)$advert->get_image_id(), [200, 50] );
        $this->mainImage = $main_thumbnail == false ? $this->no_image_parms : $main_thumbnail;
        $this->createObjectJS( $advert );
        $this->push();
      endwhile;
    }
    wp_reset_postdata();
    return $this->adverts;
  }

}
