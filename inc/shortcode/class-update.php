<?php

final class Euromada_update {
  public $product;
  public function __construct() 
  {
    $this->product = new stdClass();
  }

  public function get_product( $post ) 
  {
    if ( ! $post instanceof WP_Post) return;
    $product = wc_get_product($post->ID);
    /** Get adress */
    $this->product->localisation = new stdClass();
    $this->product->localisation->state = get_post_meta( $post->ID, '_state', true );
    $this->product->localisation->postalcode = get_post_meta( $post->ID, '_postalcode', true );
    $this->product->localisation->adress = get_post_meta( $post->ID, '_adress', true );
    /** Get product */
    $this->product->price = $product->get_price();
    $this->product->title = $product->get_title();
    $this->product->description = sanitize_textarea_field($product->get_description());
    /** Get attributes */
    $this->product->attributes = new stdClass();
    $this->product->attributes->pa_mileage = $product->get_attribute('pa_mileage');
    /** Get taxonomies */
    $this->product->terms = wp_get_post_terms( $post->ID, ['mark', 'model', 'fuel', 'gearbox', 'model-year', 'product_cat'] );
  }

  public function taxonomy_filter( $taxonomy ) 
  {
    $term = null;
    $array_terms = array_filter($this->product->terms, function( $v, $k) use ($taxonomy) {
      return $v->taxonomy == $taxonomy;
    }, ARRAY_FILTER_USE_BOTH);
    foreach ($array_terms as $terms)
    {
      $term = &$terms;
      break;
    }
    return $term;
  }

  /**
   * Template de rendu pour le shortcode
   */
  public function render( $attrs, $content ) 
  {
    $Authorization_denied = '<p class="uk-margin-remove er-h2">Vous n\'avez pas les autorisations pour afficher les contenues de cette page.</p>';
    if ( ! is_user_logged_in() || wp_doing_ajax() ) {
      echo $Authorization_denied;
      return false;
    }
    $User = wp_get_current_user();
    $post_id = Services::getValue('post_id', null);
    $post  = is_int((int)$post_id) ? get_post( $post_id ) : null;
    if (is_null($post) || $post->post_author != $User->ID) 
    {
      echo $Authorization_denied;
      return false;
    }
    if (is_null($post))
    {
      echo 'Erreur: Variable d\'identification n\'est pas definie';
      return false;
    }
    
    $this->get_product( $post );
    $categories = Services::getTerm( 'product_cat' );
    $fuels      = Services::getTerm( 'fuel' );
    $gearboxs   = Services::getTerm( 'gearbox' );
    $marks      = Services::getTerm( 'mark' );

    $modelYear = $this->taxonomy_filter('model-year');
    $fuel = $this->taxonomy_filter('fuel');
    $gearbox = $this->taxonomy_filter('gearbox');
    $mark = $this->taxonomy_filter('mark');
    $model = $this->taxonomy_filter('model');
    $categorie = $this->taxonomy_filter('product_cat');
    ?>
     <form  enctype="multipart/form-data" id="updateform" action="<?= get_permalink( (int)$post_id ) ?>" method="POST" class="ui form ">
        <?= wp_nonce_field('update', 'update_nonce') ?>
        <input type="hidden" value="<?= Services::getValue('post_id') ?>" name="post_id" />
        <h1 class="ui header">
          Modifier votre annonce
          <div class="sub header"></div>
        </h1>

        <div class="two fields">
          <div class="field">
            <label>Catégorie</label>
            <div class="ui selection dropdown">
              <input name="euromada_category" value="<?= $categorie->name ?>" type="hidden" required>
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
            <input name="euromada_title" value="<?= $this->product->title ?>" placeholder="Titre de votre annonce" 
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
              <input name="euromada_mark" value="<?= $mark->name ?>" type="hidden" required>
              <div class="menu">

              <?php foreach( $marks as $mark ): ?> 
                <div class="item" data-value="<?= $mark->name ?>"><?= strtoupper($mark->name) ?></div>
              <?php endforeach; ?>

              </div>
            </div>
          </div>
          <div class="field">
            <label>Modèle</label>
            <input placeholder="e.g X6" value="<?= $model->name ?>" name="euromada_model" type="text" autocomplete="off" required>
          </div>
        </div>

        <div class="three fields">
          <div class="field"> 
            <label>Année modèle *</label>
            <div class="ui selection dropdown">
              <div class="default text">Année modèle</div>
              <i class="dropdown icon"></i>
              <input name="euromada_year" value="<?= $modelYear->name ?>" type="hidden">
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
              <input placeholder="" value="<?= $this->product->attributes->pa_mileage ?>" name="euromada_mileage" type="text" autocomplete="off" required>
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
              <input name="euromada_fuel" value="<?= $fuel->name ?>" type="hidden" required>
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
              <input name="euromada_gearbox" value="<?= $gearbox->name ?>" type="hidden" required>
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
          <textarea rows="10" maxlength="4000" name="euromada_description"><?= $this->product->description ?></textarea>
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
              <input placeholder="Prix" value="<?= $this->product->price ?>" name="euromada_cost" id="amount" type="text" autocomplete="off" required>
              <div class="ui basic label">.00</div>
            </div>
            <div class="ui blue tiny message">
              Le champ prix doit contenir des nombres entiers (pas de point, de virgule ou d'espace)
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
              <input name="euromada_state" value="<?= $this->product->localisation->state ?>" type="hidden" required>
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
            <input value="<?= $this->product->localisation->postalcode ?>" name="euromada_postal_code" type="text" required>
          </div>
          <div class="field">
            <label>Adresse</label>
            <input value="<?= $this->product->localisation->adress ?>" name="euromada_adress" type="text" required>
          </div>
        </div>

        <div class="uk-margin">
          <div class="uk-inline"><button type="submit" class="positive ui button">Valider</button></div>
        </div>

      </form>
    <?php
  }
}