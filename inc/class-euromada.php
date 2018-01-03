<?php
class Euromada {
  function __construct() {}

  public static function taxonomy() {
    $post_type = "product";
    $taxonomies = [ "Mark", "Model", "Model Year", "Fuel", "GearBox" ];
    if (post_type_exists( $post_type )) :
      for($pos = 0; $pos < count($taxonomies); $pos++) {
        register_taxonomy(
          sanitize_title( $taxonomies[ $pos ] ),
          $post_type,
          array(
            'label' => __($taxonomies[ $pos ], 'euromada'),
            'rewrite' => array( 'slug' => sanitize_title( $taxonomies[ $pos ] ) ),
            'hierarchical' => true,
            'show_ui' => true
          )
        );
      }
    endif;
  }

}
