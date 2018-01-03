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
}