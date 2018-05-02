<?php
if ( ! defined( 'ABSPATH' ) ) 
  exit; 

$euromada = new Euromada();
$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$orders   = Services::getSession( 'orders' );

$od = [
	'meta_key' => '_price',
	'orderby'  => 'meta_value_num',
	'order'    => "ASC",
];
if ( $orders ) :
	switch ( $orders['orderby'] ):

		case "price":
			$od['order'] = $orders['order'];
			break;

		case "title";
		case "date";
			$od = [
				'orderby' => $orders['orderby'],
				'order'   => $orders['order']
			];
			break;

	endswitch;
endif;

$args = [
	'post_type'      => "product",
	'post_status'    => 'publish',
	'paged'          => $paged,
	'posts_per_page' => 8
];

$args = array_merge( $args, $od );

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
