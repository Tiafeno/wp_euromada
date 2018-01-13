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
    ?>
      <a href="<?= $logoutUrl ?>" class="button ui left floated primary button">Se deconnecter</a>
      
    <?php
    }
}