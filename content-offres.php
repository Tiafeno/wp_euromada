<?php
if ( ! defined( 'ABSPATH' ) ) 
  exit; 

$euromada = new Euromada();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = [
  'post_type'      => "product",
  'post_status'    => 'publish',
  'paged'      => $paged, 
  'posts_per_page' => 4,
];
$response = $euromada->getAdverts( $args );
$adverts = $response->adverts;

$count_products = wp_count_posts('product');
$count = $count_products->publish;

/**
 * Change badge label
 * Le nom de la variable est la post type
 */
$product = new stdClass();
$product->badge = "VOITURES D'OCCASION";

include_once get_template_directory() . '/inc/inc-offres.php';
?>
