<?php
/**
 * Author: Tiafeno Finel
 * Organisation: Entreprise FALI (Falicrea)
 * Author mail: tiafenofnel@gmail.com
 */

class Services {

	/**
	 * @desc Recupèrer des terms d'une taxonomy wordpress
	 *
	 * @param $taxonomy
	 *
	 * @return array
	 */
  public static function getTerm( $taxonomy ) {
    $term = array();
	  $parent_terms = get_terms( $taxonomy, array(
        'parent' => 0, 
        'orderby' => 'slug', 
        'hide_empty' => false, 
        'posts_per_page' => -1 
      )
	  );

	  if ( ! $parent_terms || is_wp_error( $parent_terms )){
      $parent_terms = array();
    }
    foreach ( $parent_terms as $pterm ) {
      $term[] = $pterm;
    }
    return $term;
  }

	/**
	 * @func getProductsCat
	 * @desc Recupère les categories de type de post "product" - Woocommerce
	 * @return array
	 */
  public static function getProductsCat() {
    $product_cat = [];
    $terms = get_terms( 'product_cat', [
      'parent' => 0,
      'hide_empty' => false,
      'posts_per_page' => -1
    ]);
    foreach ( $terms as $term ) {
      $content  = new stdClass();
      $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
      $image = wp_get_attachment_url( $thumbnail_id );

      $content->id = $term->term_id;
      $content->name = $term->name;
      $content->desc = $term->description;
      $content->slug = $term->slug;
      $content->image = $image;

      $url = __SEARCH_URL__ . "?s=&product_cat=" . $term->term_id;
      $content->url = $url;

      array_push( $product_cat, $content );
    }
    return $product_cat;
  }

	/**
	 * @func getValue
	 * @desc Recupère une variable d'une requete HTTP d'une methode POST ou GET
	 * @param mixed $name
	 * @param bool $def
	 *
	 * @return bool|string
	 */
  public static function getValue($name, $def = false) {
    if (!isset( $name ) || empty( $name ) || !is_string( $name ))
      return $def;
    $returnValue = isset($_POST[ $name ]) ? trim( $_POST[ $name ] ) : (isset($_GET[ $name ]) ? trim( $_GET[ $name ] ) : $def);
    $returnValue = urldecode( preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode( $returnValue )) );
    return !is_string( $returnValue ) ? $returnValue : stripslashes( $returnValue );
  }

	/**
	 * @func getSession
	 * @desc Recupère une variable session PHP
	 * @param mixed $key
	 * @param bool $default
	 *
	 * @return bool
	 */
	public static function getSession( $key, $default = false ) {
		if ( ! isset( $_SESSION[ $key ] ) || empty( $_SESSION[ $key ] ) )
			return false;
		$val = ( isset( $_SESSION[ $key ] ) ? $_SESSION[ $key ] : $default );
		return $val;
	}

	/**
	 * @desc Recupère les vignettes d'une post ou d'une produits avec ces
	 *       description, legende et taille.
	 * @param string $size
	 * @param array $ids
	 * @param null|int $post_id
	 *
	 * @return array
	 */
  public static function getThumbnails( $size = "full", $ids = [], $post_id = null ) {
    global $product;
    $thumbnails = [];

    $attachment_ids = empty($ids) ? $product->get_gallery_image_ids() : $ids;
    $id = is_null( $post_id ) ? $product->get_id() : $post_id;

    if ( $attachment_ids && has_post_thumbnail( $id ) ) {
      foreach ( $attachment_ids as $attachment_id ) {
        $full_size_image = wp_get_attachment_image_src( $attachment_id, $size );
        $title = get_post_field( 'post_title', $attachment_id );
        $excerpt = get_post_field( 'post_excerpt', $attachment_id );
        array_push($thumbnails, [ $full_size_image, $title, $excerpt ]);
      }
    }
    return $thumbnails;
  }

  public static function searchData() {
    $data = @file_get_contents( get_template_directory() . '/inc/schema/search.json');
    return json_encode( $data );
  }

	/**
	 * @desc Recupère les informations d'adresse de l'annonce ou ce post
	 * @param int $post_id
	 *
	 * @return bool|string
	 */
  public static function getLocation( $post_id ) {
    if ( ! is_numeric( $post_id )) return false;
    $adress = get_post_meta( $post_id, '_adress', true );
    $state = get_post_meta( $post_id, '_state', true );
    $codepostal = get_post_meta( $post_id, '_postalcode', true );
    $adrs =  $adress . ', ' . $state . ', ' . $codepostal;
    return ( empty($adress) AND empty($state) AND empty($codepostal) ) ? 'Aucune adresse' : $adrs;

  }

	/**
	 * @desc Recupère l'url externe de l'annonce original
	 * @param int $post_id
	 *
	 * @return false|string
	 */
  public static function get_recommandation_source_url( $post_id ) {
    if ( ! is_numeric( $post_id )) return false;
    $lnk = get_post_meta( $post_id, 'link_recommandation', true );
    return empty( $lnk ) ? '#lnk' : $lnk;
  }

	/**
	 * @desc Recupère les taxonomies du post de type product (Woocommerce)
	 * @return array
	 */
  public static function getObjectTerms() {
	  global $post;
    $objectTerms = [];
    $taxonomies = Euromada::$taxonomies;
    while(list(, $taxonomy) = each($taxonomies)) {
      $product_term = wp_get_object_terms( $post->ID,  sanitize_title($taxonomy) );
      if ( ! is_wp_error( $product_term ) ) {
        foreach( $product_term as $term ) {
          $attr = new stdClass();
          // $attr->id = $term->term_id;
          $attr->name = $term->name;
          $attr->taxonomy = $term->taxonomy;
          array_push($objectTerms, $attr);
        }
      }
    }
    
    return $objectTerms;
  }
}