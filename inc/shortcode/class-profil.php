<?php

require get_template_directory() . '/inc/shortcode/includes/euromada-actions.php';

final class Euromada_profil {
  public function __construct() {}

  public static function deletePost( $post_id ) {
    $token = Services::getValue('token');
    if ( ! is_user_logged_in()) return false;
    $User = wp_get_current_user();
    if ($token != wp_get_session_token()) return false;
    $post = get_post($post_id);
    if ( ! $post instanceof WP_Post) return false;
    if ($post->post_author != $User->ID) return false;
    wp_delete_post( $post_id, false); // Move to trash
  }

  public static function render($attrs, $content = "") {
    $euromadaActions = new euromada_actions();
    $euromada = new Euromada();
    wp_enqueue_script( 'euromada-profil-script', get_template_directory_uri() . '/profil.js', array( 'vuejs', 'vuejs-route', 'jquery', 'euromada-script' ), '', true );
    
    $my_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
      'numberposts' => 12,
      'meta_key'    => '_customer_user',
      'meta_value'  => get_current_user_id(),
      'post_type'   => wc_get_order_types( 'view-orders' ),
      'post_status' => array_keys( wc_get_order_statuses() ),
    ) ) );

    add_action('euromada_mes_annonces', [ $euromadaActions, 'action_mes_annonces']);
    add_action('euromada_my_profil', [ $euromadaActions, 'action_my_profil']);

    ?>  
    <script type="text/javascript">
    var __user_token__ = "<?= wp_get_session_token() ?>";
    var __adverts__ = <?= json_encode( $euromada->myAdverts(), JSON_PRETTY_PRINT ); ?>;

    (function($) {
      $(document).ready(function() {
        /** ready, code here */
      })
    })(jQuery)
    
    </script>

  <!-- Dialog -->
      <div class="ui basic commande modal">
        <div class="ui icon header">
          <i class="archive icon"></i>
          Archive Old Messages
        </div>
        <div class="content">
          <p>Your inbox is getting full, would you like us to enable automatic archiving of old messages?</p>
        </div>
        <div class="actions">
          <div class="ui red basic cancel inverted button">
            <i class="remove icon"></i>
            No
          </div>
          <div class="ui green ok inverted button">
            <i class="checkmark icon"></i>
            Yes
          </div>
        </div>
      </div>

      <div class="ui basic delete modal">
        <div class="ui icon header">
          <i class="remove circle icon"></i>
        </div>
        <div class="content">
          <p>Voulez vous vraiment supprimer cette publication?</p>
        </div>
        <div class="actions">
          <div class="ui green ok inverted button">
            <i class="checkmark icon"></i>
            Yes
          </div>
        </div>
      </div>
<!--  end Dialog - -->

    <h2 class="ui header">
      <i class="settings icon"></i>
      <div class="content">
        Paramètres du compte
        <div class="sub header">Gérer vos préférences et vos annonces</div>
      </div>
    </h2>
      <ul uk-tab>
          <li><a href="#">Mes annonces</a></li>
          <li><a href="#">Mes commandes</a></li>
          <li><a href="#">Mon profil</a></li>
      </ul>
      
      <ul class="uk-switcher uk-margin" id="app-profil">
          <li>
            <?php do_action('euromada_mes_annonces'); ?>
          </li>

          <li>
            <!-- Orders -->
          
              <table class="ui single line table">
                <thead>
                  <tr>
                    <th>Numéro</th>
                    <th>Date de commande</th>
                    <th>État de la commande</th>
                    <th>Qté</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
          <?php foreach ( $my_orders as $my_order ) :
                  $order      = wc_get_order( $my_order );
                  $item_count = $order->get_item_count();
              ?>
                  <tr>
                    <td>
                      <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                        <?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
                      </a>
                    </td>
                    <td>
                      <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                    </td>
                    <td>
                      <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                    </td>
                    <td>
                      <?php printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );?>
                    </td>
                    <td>
                      <?php
                        $actions = wc_get_account_orders_actions( $order );
                        
                        if ( ! empty( $actions ) ) {
                          foreach ( $actions as $key => $action ) {
                            echo '<a @click="voirCommande( ' . $my_order->ID . ' )" class="button ui right floated primary button er-button-voir ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                          }
                        }
                      ?>
                    </td>
                  </tr>
            <?php endforeach; ?>
                </tbody>
              </table>
          </li>

          <li>
            <?php do_action('euromada_my_profil'); ?>
          </li>
      </ul>

    <?php
  }
}