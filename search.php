<?php
/**
 * The template for displaying search results pages.
 *
 */
get_header();

global $wp_query;


 /** Join for searching metadata */
//function join_postmeta_query($join) {
// global $wp_query, $wpdb;
// if (isset($wp_query->query_vars['s'])) {
//     $join .= "LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
// }
// return $join;
//}
//add_filter('posts_join', 'join_postmeta_query', 10, 2);
//
//
//add_filter( 'posts_where' , 'posts_where', 10, 2 );
//function posts_where( $where ) {
// global $wpdb, $wp_query;
// $price_limite = (int)Services::getValue("maxprice");
// if (isset($wp_query->query_vars['s'])) {
//   $where .= " AND $wpdb->postmeta.meta_key = '_regular_price' ";
// }
// return $where;
//}


$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$tax_query = [ 'relation' => 'AND' ];
$names = ["mark", "fuel", 'product_cat'];
$meta_query = [];

while (list(, $name) = each($names)) :
  $value = Services::getValue( $name );
  if (false != $value)
    array_push( $tax_query, [ 'taxonomy' => $name, 'field' => 'term_id', 'terms' => [(int)$value] ]);
endwhile;

/**
 * Requete dans postmeta pour l'entrer "_price",
 * comparaison numerique
 */
if (false != Services::getValue('maxprice')) {
  /**
   * Limite et non limite de prix
   */
  $maxPrice = Services::getValue('maxprice');

  array_push($meta_query, [
    'key' => "_price",
    'value' => (float)Services::getValue('maxprice'),
    'compare' => "<=",
    'type' => "NUMERIC"
  ]);
}

/**
 * Rechercher les mots clé de la recherche dans la taxonomy model
 * du produit.
 */
if ( ! empty($wp_query->query_vars['s']) ) {
  $s = $wp_query->query_vars['s'];
  $array_query = explode( ' ', $s );
  array_push( $tax_query, [
    'taxonomy' => 'model',
    'field' => 'name',
    'terms' =>  $array_query,
    'operator' => 'EXISTS'
  ]);
}

/**
 * Assemblage
 */
$args = array(  
  'post_type'  => [ 'product' ],
  'meta_query' => $meta_query,
  's'          => $wp_query->query_vars['s'],
  'paged'      => $paged, 
  'posts_per_page' => 20,
  'tax_query'  => $tax_query
);

query_posts( $args );
// echo $wp_query->request;

$argr = [
  'post_type' => [ 'recommandation' ],
  's'         => $wp_query->query_vars['s'],
  'posts_per_page' => 10
];
$recommandations = new WP_Query( $argr );
?>
      <div id="primary-content">
        <div class="uk-section uk-section-large uk-padding-medium">
          <div class="uk-container uk-container-small">
          <?php
            if ( have_posts() ) :
              set_query_var('badge', get_post_type());
              if ($recommandations->have_posts()) :
                set_query_var('recommandations', $recommandations);
              endif;
              get_template_part( 'woocommerce/content', 'archive-product' ); 
            endif;
          ?>
          </div>
        </div>
      </div>
      <?php get_footer(); ?>
    </div>
    <?php wp_footer(); ?>
  </div>
</body>
</html>