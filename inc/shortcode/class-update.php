<?php

final class Euromada_update {
  public function __construct() 
  {

  }

  /**
   * Template de rendu pour le shortcode
   */
  public static function render( $attrs, $content ) 
  {
    if ( ! is_user_logged_in()) {
      echo '<p class="uk-margin-remove er-h2">Vous n\'avez pas les autorisations pour afficher les contenues de cette page.</p>';
      return false;
    }
    
  }
}