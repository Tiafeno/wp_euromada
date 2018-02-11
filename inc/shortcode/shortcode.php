<?php
add_shortcode('app_custom', 'app_custom');
function app_custom( $attrs, $content ) {
  $attributs = shortcode_atts([], $attrs);
  ?>
  
  <div id="app-custom">
    <annonces></annonces>
  </div>

  <?php
}