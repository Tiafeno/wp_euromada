<?php
class Euromada {
  public static $taxonomies = [ "Mark", "Model", "Model Year", "Fuel", "GearBox" ];

  public $full_size_gallery = [];
  public $thumbnail_gallery = [];
  public $adverts = []; // return value
  public $mainImage;
  public $contents;

  function __construct() {}

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

  protected function push() {
    array_push( $this->adverts, $this->contents );
  }

  protected function createObjectJS( &$advert ) {
    $this->contents = new stdClass();
    $this->contents->id = $advert->get_id();
    $this->contents->title = $advert->get_title();
    $this->contents->cost = $advert->get_price();
    $this->contents->countPic = count( $this->full_size_gallery );
    $this->contents->description = $advert->get_description();
    $this->contents->dateadd = $advert->get_date_created();
    $this->contents->imgLink = $this->mainImage[0][0];
    $this->contents->url = get_the_permalink( $advert->get_id() );
    $this->contents->attributes = Services::getObjectTerms();
    /** set in adverts varibale */
    
  }

  protected function getMainThumbnail( $id, $size = "full" ) {
    $image   = wp_get_attachment_image_src( $id, $size );
    $title   = get_post_field( 'post_title', $id );
    $excerpt = get_post_field( 'post_excerpt', $id );
    return [ $image, $title, $excerpt ];
  }

  public function getAdverts() {
    $index = ( get_query_var( 'index' ) ) ? get_query_var( 'index' ) : 1;
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => 12,
      'paged'          => $index
    );
    query_posts($args); 
    if (have_posts()) {
      while (have_posts()) : the_post();
        
        $advert = wc_get_product(get_the_ID());

        $this->full_size_gallery = Services::getThumbnails();
        $this->mainImage = $this->getMainThumbnail( (int)$advert->get_image_id(), [600, 300] );
        array_push( $this->full_size_gallery, $this->mainImage );

        $this->createObjectJS( $advert );
        $this->push();
      endwhile;
    }

    return $this->adverts;
    
  }
}
