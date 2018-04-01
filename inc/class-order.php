<?php
/**
 * Author: Tiafeno Finel
 * Organisation: Entreprise FALI (Falicrea)
 * Author mail: tiafenofnel@gmail.com
 */
final class Euromada_Order
{
  public function __construct()
  {
    return $this;
  }

  /**
   * This function add product in cart
   *
   * @param int - Product ID
   * @return void
   */
  public function addCart($post_id)
  {
    global $woocommerce;
    if (!is_int($post_id)) return;
    $post = get_post($post_id);
    if ($post->post_type == "product") :
      $cart_item_key = $woocommerce->cart->add_to_cart($post_id, 1);
    endif;
  }

  /**
   * Determine if a product exists based on title and taxonomy
   * @param string $post_title
   * @param string $post_type
   * @return int
   */
  private static function product_exists($post_title, $post_type = "product")
  {
    global $wpdb;

    $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
    $args = array();

    if (!empty ($post_title)) {
      $query .= " AND post_title LIKE '%s' ";
      $args[] = $post_title;
    }

    if (!empty ($post_type)) {
      $query .= " AND post_type = '%s' ";
      $args[] = $post_type;
    }

    if (!empty ($args))
      return $wpdb->get_var($wpdb->prepare($query, $args));

    return 0;
  }

  /**
   * Create an product post
   * 
   * @param int - $post_id This is a recommandation ID post type
   * @return int - Product ID
   */
  public static function createProduct($post_id)
  {
    if ( ! is_user_logged_in()) return false;
    if ( ! is_int( $post_id) ) return new WP_Error('broken', "Variable isn't integer");
    $user = get_current_user();
    $pst = get_post( $post_id );
    $attachment_id = get_post_thumbnail_id( $pst );
    /** return wp error if post type isn't `recommandation` */
    if ($pst->post_type != "recommandation") return new WP_Error("broke", "Can't create a product from this type of item");
    $cost = get_post_meta( $post_id, 'cost_recommandation', true );

    /** verify post if exist */
    $post_exist = self::product_exists($pst->post_title);
    if ($post_exist != 0) return $post_exist;
    $post = [
      'post_author' => $user->ID,
      'post_type' => 'product',
      'post_status'  => 'private',
      'post_title'   => $pst->post_title,
      'post_content' => $pst->post_content
    ];

    /* Insert the post for new private product post */
    $current_post_id = wp_insert_post( $post, true );
    $user = wp_get_current_user();
    wp_set_object_terms($current_post_id, 'simple', 'product_type');

    /* Update post meta, these meta depend a product post_type */
    update_post_meta( $current_post_id, '_visibility', 'visible');
    update_post_meta( $current_post_id, '_stock_status', 'instock');
    update_post_meta( $current_post_id, 'total_sales', '0');
    update_post_meta( $current_post_id, '_downloadable', 'no');
    update_post_meta( $current_post_id, '_virtual', 'yes');
    update_post_meta( $current_post_id, '_regular_price', $cost);
    update_post_meta( $current_post_id, '_sale_price', '');
    update_post_meta( $current_post_id, '_purchase_note', '');
    update_post_meta( $current_post_id, '_featured', 'no');
    update_post_meta( $current_post_id, '_weight', '');
    update_post_meta( $current_post_id, '_length', '');
    update_post_meta( $current_post_id, '_width', '');
    update_post_meta( $current_post_id, '_height', '');
    update_post_meta( $current_post_id, '_sku', strtoupper( md5( $current_post_id )) );
    update_post_meta( $current_post_id, '_sale_price_dates_from', '');
    update_post_meta( $current_post_id, '_sale_price_dates_to', '');
    update_post_meta( $current_post_id, '_price', $cost);
    update_post_meta( $current_post_id, '_sold_individually', '');
    update_post_meta( $current_post_id, '_manage_stock', 'no');
    update_post_meta( $current_post_id, '_backorders', 'no');
    update_post_meta( $current_post_id, '_stock', '');
    update_post_meta( $current_post_id, '_product_image_gallery', "");
    update_post_meta( $current_post_id, '_thumbnail_id', $attachment_id); 

    if (is_wp_error( $current_post_id )) {
      return new WP_Error('broken', 'An error occurred while creating the product');
    }
    /** return product_id or post_id product */
    return $current_post_id;
  }
}