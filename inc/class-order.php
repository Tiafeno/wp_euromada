<?php
/**
 * Author: Tiafeno Finel
 * Organisation: Entreprise FALI (Falicrea)
 * Author mail: tiafenofnel@gmail.com
 */


final class Euromada_Order {
  public function __construct() {}
  
  public function addCart( $post_id ) {
    global $woocommerce;
    if ( ! is_int($post_id)) return;
    $post = get_post( $post_id );
    if ($post->post_type == "product") :
      $cart_item_key = $woocommerce->cart->add_to_cart( $post_id, 1 );
    endif;
  }
}