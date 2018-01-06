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

  function __construct() {
    $this->contents = new stdClass();
  }

  /**
   * Create dependancy taxonomies for this template
   * @return {void}
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
   * Push the dependancy content in global array variable.
   * @param {void}
   * @return {void}
   */
  protected function push() {
    array_push( $this->adverts, $this->contents );
  }

  /**
   * Add the dependancy in contents object
   * @param {object} - Woocommerce product class
   * @return {void}
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
   * @param {int $id, string|array $size} - $id is product or post ID
   * @return {array}
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
   * @param {void}
   * @return {array} - Array of object product details
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
    wp_reset_query();

    /** return all products details pushed  */
    return $this->adverts;
    
  }

  /**
   * Get single product details
   * @param {void}
   * @return {object}
   */
  public function getAdvert() {
    $advert = wc_get_product(get_the_ID());
    $this->full_size_gallery = Services::getThumbnails();
    $this->thumbnail_gallery = Services::getThumbnails( [300, 300] );

    $this->mainImage = $this->getMainThumbnail( (int)$advert->get_image_id(), "full" );
    array_push( $this->full_size_gallery, $this->mainImage );

    $thumbnail_main = $this->getMainThumbnail( (int)$advert->get_image_id(), [300, 300]);
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
