<?php
/**
 * The template for displaying search results pages.
 *
 */
get_header();

global $wp_query;


// Join for searching metadata
// function join_postmeta_query($join) {
//   global $wp_query, $wpdb;
//   if (isset($wp_query->query_vars['s'])) {
//       $join .= "LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
//   }
//   return $join;
// }
// add_filter('posts_join', 'join_postmeta_query', 10, 2);


// add_filter( 'posts_where' , 'posts_where', 10, 2 );
// function posts_where( $where ) {
//   global $wpdb;
//   $price_limite = (int)Services::getValue("maxprice");
//   if (isset($wp_query->query_vars['s'])) {
//     $where .= " AND $wpdb->postmeta.meta_key = '_regular_price' ";
//   }
//   return $where;
// }

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$tax_query = [ 'relation' => 'AND' ];
$names = ["mark", "model", "fuel"];

while (list(, $name) = each($names)) :
  $value = Services::getValue( $name );
  if (false != $value)
    array_push( $tax_query, [ 'taxonomy' => $name, 'field' => 'term_id', 'terms' => [(int)$value] ]);
endwhile;

$args = array(  
  'post_type' => 'product', 
  's' => $wp_query->query_vars['s'],
  'paged' => $paged, 
  'posts_per_page' => -1,
  'tax_query' => $tax_query
);
query_posts( $args );
// echo $wp_query->request;

?>
    <div id="primary-content">
      <div class="uk-section uk-section-large uk-padding-medium">
        <div class="uk-container uk-container-small">
        <?php
          if ( have_posts() ) :
            get_template_part( 'woocommerce/content', 'archive-product' ); 
          endif;
        ?>
        </div>
      </div>
    </div>
    <?php get_footer(); ?>
  </div>
  <?php wp_footer(); ?>
</body>
</html>