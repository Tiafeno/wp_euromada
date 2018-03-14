<?php
/**
 * The template for displaying search results pages.
 *
 */
get_header();

global $wp_query;

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
$compare  = "<=";
if (false != Services::getValue('maxprice')) {
  /**
   * Limite et non limite de prix
   */
  $maxPrice = (float)Services::getValue( 'maxprice' );
  $optionMaxPrice = (float)get_option( 'max_price', 0 );
  if ($optionMaxPrice != 0 && $maxPrice === $optionMaxPrice) $compare = ">=";
  array_push($meta_query, [
    'key' => "_price",
    'value' => $maxPrice,
    'compare' => $compare, // droite
    'type' => "NUMERIC"
  ]);
}
// if (false != Services::getValue('minprice')) {
//   $min_price = (float)Services::getValue('minprice');
//   /**
//    * Convertion de MGA en EUR
//    */
  
//   array_push($meta_query, [
//     'key' => "_price",
//     'value' => $min_price,
//     'compare' => '=>', // droite
//     'type' => "NUMERIC"
//   ]);
// }

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

$argr = [
  'post_type' => [ 'recommandation' ],
  's'         => $wp_query->query_vars['s'],
  'posts_per_page' => 10
];
$recommandations = new WP_Query( $argr );
wp_reset_postdata();


query_posts( $args );
// echo $wp_query->request;

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
              wc_get_template_part( 'content', 'archive-product' );
            else:
              echo '<h2>Aucune annonce trouvée !</h2>';
              echo 'Si vous effectuez une recherche par mots-clés, vérifiez bien qu\'il n\'y ait pas de faute de frappe.';
              echo '<div id="app-custom"><annonces></annonces></div>';
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