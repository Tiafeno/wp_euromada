<?php

class Services {
  public static function getTerm( $taxonomy ) {
    $term = array();
    $parent_terms = \get_terms( $taxonomy, array( 'parent' => 0, 'orderby' => 'slug', 'hide_empty' => false ) );
    if (!$parent_terms || \is_wp_error( $parent_terms )){
      $parent_terms = array();
    }
    foreach ( $parent_terms as $pterm ) {
      $term[] = $pterm;
    }
    return $term;
  }

  public static function getThumbnails( $size = "full" ) {
    global $post, $product;
    $thumbnails = [];

    $attachment_ids = $product->get_gallery_image_ids();

    if ( $attachment_ids && has_post_thumbnail() ) {
      foreach ( $attachment_ids as $attachment_id ) {
        $full_size_image = wp_get_attachment_image_src( $attachment_id, $size );
        $title = get_post_field( 'post_title', $attachment_id );
        $excerpt = get_post_field( 'post_excerpt', $attachment_id );
        array_push($thumbnails, [ $full_size_image, $title, $excerpt ]);
      }
    }
    return $thumbnails;
  }

  public static function getObjectTerms() {
    global $post, $product;
    $objectTerms = [];
    $taxonomies = Euromada::$taxonomies;
    while(list(, $taxonomy) = each($taxonomies)) {
      $product_term = wp_get_object_terms( $post->ID,  sanitize_title($taxonomy) );
      if ( ! is_wp_error( $product_term ) ) {
        foreach( $product_term as $term ) {
          $object = new stdClass();
          $object->attribut = $taxonomy;

          array_push($objectTerms, $term);
        }
      }
    }
    
    return $objectTerms;
  }
}