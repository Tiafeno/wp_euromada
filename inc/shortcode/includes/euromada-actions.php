<?php

class euromada_actions {
  public function __construct(){}

    public function action_mes_annonces() {
    ?>
      <div class="ui divided items">
        <div class="item" v-for="(advert, index) in adverts">
          <div class="image">
            <p class="er-photo">{{ advert.countPic }}</p>
            <img v-bind:src="advert.imgLink" v-bind:alt="advert.title">
          </div>
          <div class="content">
            <div class="extra">
              <div class="ui left floated"><a v-bind:href="advert.url" class="header er-list-title">{{ advert.title }}</a></div>
              <div class="ui right floated er-h2" style="color: #000"><p>{{ advert.cost | ariary }}</p></div>
            </div>
            <div class="meta">
              <span class="cinema">{{ advert.dateadd.date | moment }}</span>
            </div>
            <div class="description">
              <p></p>
            </div>
            <div class="extra">
              <div class="ui right floated primary button er-button-voir" @click="window.location.href = advert.url">Voir</div>
              <div class="ui right floated negative button" @click="deletePost(advert.id)">Supprimer</div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

    public function action_my_profil() {
      $logoutUrl = wp_logout_url( home_url('/') );  
      $User = wp_get_current_user();
      $adress = get_user_meta($User->ID, '_adress_', true);
      $phone = get_user_meta($User->ID, '_phone_', true);
    ?>
      <div class="uk-container">
       <a href="<?= $logoutUrl ?>" class="button ui left floated primary button">Se deconnecter</a>
      </div>
      <div class="uk-width-2-3@s">
        <form id="editProfilForm" action="" method="POST" class="ui form">
          <?= wp_nonce_field('edit_profil', 'edit_profil_nonce') ?>
          <h4 class="ui dividing header">Informations personnelles</h4>

          <div class="two fields">
            <div class="field"> <!-- error -->
              <label>Nom de famille</label>
              <input placeholder="" name="firstname" type="text" value="<?= $User->first_name ?>">
            </div>
            <div class="field">
              <label>Prénom</label>
              <input placeholder="" name="lastname" type="text" value="<?= $User->last_name ?>">
            </div>
          </div>

          <div class="field">
            <label>Adrèsse E-mail</label>
            <div class="fields">
              <div class="twelve wide field">
                <input type="text" name="email" placeholder="Adresse email" value="<?= $User->user_email ?>" disabled>
              </div>
            </div>
          </div>

          <div class=" fields">
            <div class="field">
              <label>Adrèsse de facturation</label>
              <input placeholder="Adresse" name="adress" value="<?= $adress ?>" type="text">
            </div>
            <div class="field">
              <label>Numéro de téléphone</label>
              <input placeholder="Votre numéro" name="phone" value="<?= $phone ?>" type="text">
            </div>
          </div>
          <button class="ui blue button" type="submit">Enregistrer</button>
        </form>
      </div>
    <?php
    }
}