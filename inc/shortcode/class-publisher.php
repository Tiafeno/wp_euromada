<?php

class Euromada_Publisher {
  public function __construct() {}
  public static function render() {
    $logged = is_user_logged_in() ? true : false;
    if ($logged) :
      $categories = Services::getTerm( 'product_cat' );
      $fuels      = Services::getTerm( 'fuel' );
      $gearboxs   = Services::getTerm( 'gearbox' );
      $marks      = Services::getTerm( 'mark' );
    endif;

    ?>
    <div>
    <?php if ( ! $logged) : ?>
      <?php do_shortcode( '[euromada_login]' ) ?>
    <?php else: ?>
      <form  enctype="multipart/form-data" id="publishform" action="" method="POST" class="ui form ">
        <?= wp_nonce_field('publish', 'publish_nonce') ?>
        <h4 class="ui dividing header">Votre annonce</h4>

        <div class="two fields">
          <div class="field">
            <label>Catégorie</label>
            <div class="ui selection dropdown">
              <input name="euromada_category" value="<?= Services::getValue('euromada_category','') ?>" type="hidden" required>
              <div class="default text">Catégorie</div>
              <i class="dropdown icon"></i>
              <div class="menu">
              <?php foreach ($categories as $categorie) { ?>
                <div class="item" data-value="<?= $categorie->name ?>">
                  <!-- <i class="visa icon"></i> -->
                  <?= $categorie->name ?>
                </div>
              <?php } ?>
              </div>
            </div>
          </div>
        </div>
        
        <div class="two fields">
          <div class="field">
            <label>Titre de l'annonce</label>
            <input name="euromada_title" value="<?= Services::getValue('euromada_title','') ?>" placeholder="Titre de votre annonce" 
            type="text" autocomplete="off">
            <div class="ui blue tiny message">
              Votre annonce sera refusée si le titre ne décrit pas précisément le poduit que vous
              proposez. Ne pas mentionner "Vente" ou "Achat" dans le titre.
            </div>
          </div>
        </div>

        <div id="app-dynamic-select-mark" class="three fields">
          <div class="field"> 
            <label>Marque *</label>
            <div class="ui fluid search selection dropdown">
              <div class="default text">Marque</div>
              <i class="dropdown icon"></i>
              <input name="euromada_mark" value="<?= Services::getValue('euromada_mark','') ?>" type="hidden" required>
              <div class="menu">

              <?php foreach( $marks as $mark ): ?> 
                <div class="item" data-value="<?= $mark->name ?>"><?= strtoupper($mark->name) ?></div>
              <?php endforeach; ?>

              </div>
            </div>
          </div>
          <div class="field">
            <label>Modèle</label>
            <input placeholder="e.g X6" value="<?= Services::getValue('euromada_model','') ?>" name="euromada_model" type="text" autocomplete="off" required>
          </div>
        </div>

        <div class="three fields">
          <div class="field"> 
            <label>Année modèle *</label>
            <div class="ui selection dropdown">
              <div class="default text">Année modèle</div>
              <i class="dropdown icon"></i>
              <input name="euromada_year" value="<?= Services::getValue('euromada_year','') ?>" type="hidden">
              <div class="menu">

              <?php foreach( range(1960, (int)date( "Y" )) as $year): ?> 
                <div class="item" data-value="<?= $year ?>"><?= $year ?></div>
              <?php endforeach; ?>

              </div>
            </div>
          </div>
          <div class="field">
            <label>Kilométrage *</label>
            <div class="ui right labeled input">
              <input placeholder="" value="<?= Services::getValue('euromada_mileage','') ?>" name="euromada_mileage" type="text" autocomplete="off" required>
              <div class="ui basic label">KM</div>
            </div>
          </div>
        </div>

        <div class="three fields">
          <div class="field"> 
            <label>Carburant *</label>
            <div class="ui selection dropdown">
              <div class="default text">Carburant</div>
              <i class="dropdown icon"></i>
              <input name="euromada_fuel" value="<?= Services::getValue('euromada_fuel','') ?>" type="hidden" required>
              <div class="menu">

              <?php foreach ($fuels as $fuel) { ?>
                <div class="item" data-value="<?= $fuel->name ?>"><?= $fuel->name ?></div>
              <?php } ?>

              </div>
            </div>
          </div>

          <div class="field">
            <label>Boîte de vitesse *</label>
            <div class="ui selection dropdown">
              <div class="default text">Boîte de vitesse</div>
              <i class="dropdown icon"></i>
              <input name="euromada_gearbox" value="<?= Services::getValue('euromada_gearbox','') ?>" type="hidden" required>
              <div class="menu">

              <?php foreach ($gearboxs as $gearbox) { ?>
                <div class="item" data-value="<?= $gearbox->name ?>"><?= $gearbox->name ?></div>
              <?php } ?>

              </div>
            </div>
          </div>
        </div>

        <!-- <input class="test" accept="image/bmp,image/gif,image/jpeg,image/png,image/x-ms-bmp" name="file[]" id="text" type="file"> -->

        <div class="field">
          <label>Texte de l'annonce *</label>
          <textarea rows="10" maxlength="4000" name="euromada_description"><?= Services::getValue('euromada_description','') ?></textarea>
          <div class="ui black tiny message">
            Indiquez dans le texte de l’annonce si vous proposez un droit de rétractation à l’acheteur. 
            En l’absence de toute mention, l’acheteur n’en bénéficiera pas et ne pourra pas demander le remboursement 
            ou l’échange du bien ou service proposé.
          </div>
        </div>

        <div class="two fields">
          <div class="field">
            <label>Prix *</label>
            <div class="ui right labeled input">
              <label for="amount" class="ui label">EUR</label>
              <input placeholder="Prix" value="<?= Services::getValue('euromada_cost','') ?>" name="euromada_cost" id="amount" type="text" autocomplete="off" required>
              <div class="ui basic label">.00</div>
            </div>
            <div class="ui blue tiny message">
              Le champ prix doit contenir des nombres entiers (pas de point, de virgule ou d'espace)
            </div>
          </div>
        </div>
        <p><b>Photos</b> : Une annonce avec photo est 7 fois plus consultée qu'une annonce sans photo</p>
        <div class="three fields">
          <div>
           <p class="ui orange tiny message">La taille du fichier ne doit pas dépasser 16Mo.</p>
          </div>
        </div>
        

        <div id="app-publish">
          <div id="pictures-list" class="ui small images">

            <div class="ctn" v-for="picture in pictures">
              <div class="er-remove-picture uk-hidden" @click="remove($event, picture.identification)">
                <img class="uk-icon er-icon-trash" src="<?= get_template_directory_uri() . '/img/trash.png' ?>" >
              </div>
              <span v-upload:identification="picture.identification" class="directive">
                <img class="ui image" data-state="not_uploaded" v-bind:src="imageNoUploaded">
                <div class="uk-hidden">
                  <input class="picture" name="euromada_images[]" v-bind:id="picture.identification" type="file">
                </div>
              </span>
            </div>
          </div>
        </div>

        <h4 class="ui dividing header">Localisation de cette annonce</h4>
        <div class="three fields">
          <div class="field">
            <label>Pays *</label>
            <div class="ui fluid search selection dropdown">
              <div class="default text">Pays</div>
              <i class="dropdown icon"></i>
              <input name="euromada_state" value="<?= Services::getValue('euromada_state','') ?>" type="hidden" required>
              <div class="menu">

              <?php foreach( unserialize(STATES) as $state): ?> 
                <div class="item" data-value="<?= $state ?>"><?= $state ?></div>
              <?php endforeach; ?>

              </div>
            </div>
            <div class="ui black tiny message">
              Indiquez le pays où se trouve l'annonce que vous proposez.
            </div>
          </div>
        </div>

        <div class="three fields">
          <div class="field"> 
            <label>Ville ou code postal</label>
            <input value="<?= Services::getValue('euromada_postal_code','') ?>" name="euromada_postal_code" type="text" required>
          </div>
          <div class="field">
            <label>Adresse</label>
            <input value="<?= Services::getValue('euromada_adress','') ?>" name="euromada_adress" type="text" required>
          </div>
        </div>

        <div class="uk-margin">
          <div class="uk-inline"><button type="submit" class="positive ui button">Valider</button></div>
        </div>

      </form>
   <?php endif; ?>
    </div>
    <style type="text/css">
      .ctn:first-child::before {
        content: "PHOTO PRINCIPAL";
        display: inline-block;
        position: absolute;
        color: #fff !important;
        width: inherit;
        z-index: 9999;
        background: #4aad2b;
        font-size: 12px;
        padding-left: 10px;
        padding-right: 10px;
      }
      #pictures-list div.ctn, div.ctn > span.directive {
        display: inline-block;
        width: inherit;
        position: relative;
        cursor: pointer;
      }
      #pictures-list .ui.image {
        margin: auto;
        padding: 5px;
      }
      div.ctn {
        margin-right: 20px;
        border: 1px dashed #d1cece;
      }
      .uk-icon-button {
        width: 25px !important;
        height: 25px !important;
      }
      #pictures-list .er-remove-picture {
        display: inline-block;
        position: absolute;
        right: -10px;
        top: -10px;
        z-index: 99;
        width: 26px;
      }
      .er-icon-trash {
        margin: 0;
        padding: 5px;
        background: #fbff49;
        border-radius: 20px;
      }
      .ui.tiny.message {
        box-shadow: none !important;
      }
    </style>
    <?php
  }
}