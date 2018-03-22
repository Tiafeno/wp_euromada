<?php
/**
 * Template Name: Search Page
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

/**
 * Rechercher les mots clé de la recherche dans la taxonomy model
 * du produit.
 */
$query = Services::getValue( "query", '' );

// Taxonomy query
if ( ! empty($query) ) {
  $array_query = explode( ' ', $query );
  array_push( $tax_query, [
    'taxonomy' => 'model',
    'field' => 'name',
    'terms' =>  $array_query,
    'operator' => 'EXISTS'
  ]);
}
/** Order */
$od = [
  'meta_key'   => '_price',
  'orderby'    => 'meta_value_num',
  'order'      => "ASC",
];

/**
 * Assemblage
 */
$args = array(  
  'post_type'  => [ 'product' ],
  'meta_query' => $meta_query,
  's'          => $query,
  'paged'      => $paged, 
  'posts_per_page' => -1,
  'tax_query'  => $tax_query
);

$args = array_merge($args, $od);

$argr = [
  'post_type' => [ 'recommandation' ],
  's'         => $query,
  'posts_per_page' => 10
];
$recommandations = new WP_Query( $argr );
wp_reset_postdata();


// query_posts( $args );
$euromada = new Euromada();

$response = $euromada->getAdverts( $args );
$adverts = $response->adverts;
$count = count($adverts);
// echo $wp_query->request;

?>
      <div id="primary-content">
        <div class="uk-section uk-section-large uk-padding-medium">
          <div class="uk-container uk-container-small">
          <?php 
            if ( $count > 0 ) :
              echo "<h4>Résultats de recherche pour « <i>". $query ."</i> ».</h4>";
              include_once get_template_directory() . '/inc/inc-offres.php';
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