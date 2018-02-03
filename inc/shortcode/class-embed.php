<?php

class Euromada_embed {
  public function __construct() {}
  public static function render( $attrs ) {
    global $wp_query;
    $ref_url = Services::getValue( 'ref_url' );
    $a = shortcode_atts( array(
      'url' => 'something'
    ), $attrs );
    ?>

    <iframe id="Example2"
        name="Example2"
        title="Example2"
        width="100%"
        height="500"
        frameborder="0"
        scrolling="yes"
        marginheight="0"
        marginwidth="0"
        src="<?= $ref_url ?>">
    </iframe>

    <?php
  }
}