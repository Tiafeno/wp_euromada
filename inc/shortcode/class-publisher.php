<?php

class Euromada_Publisher {
  public function __construct() {}
  public static function render() {
    ?>
    <form id="publishform" action="" method="POST" class="ui form">
      <?= wp_nonce_field('publish', 'publish_nonce') ?>
      <h4 class="ui dividing header">Informations</h4>

      <div class="two fields">
          <div class="field"> 
            <label>Titre</label>
            <input placeholder="" name="firstname" type="text">
          </div>
          <div class="field">
            <label>Prénom</label>
            <input placeholder="" name="lastname" type="text">
          </div>
        </div>
    </form>

    <?php
  }
}