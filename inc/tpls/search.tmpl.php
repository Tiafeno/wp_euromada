<?php
?>
<section id="app-search" class="er-search uk-padding-medium uk-padding-remove-left uk-padding-remove-right">
  <!-- search section -->
  <div class="uk-section-transparent uk-section-large uk-margin-remove uk-padding-remove">
    <div class="uk-container uk-container-small">
      <form class="uk-grid-small" method="GET" action="<?= site_url('/'); ?>" uk-grid>
        <div class="uk-width-1-5@m">
          <div class="ui input uk-display-block">
            <input placeholder="Que recherchez-vous?" name="s" value="<?= Services::getValue( 's', '' ) ?>" style="border-radius: 0; width: 100%" />
            
          </div>
        </div>
        <div class="uk-width-1-5@m">
            <select name="product_cat" class="ui fluid normal dropdown">
              <option value="">Catégorie</option>
              <option v-for="cat in selectInput.categories" v-bind:value="cat.term_id">
                {{cat.name}}
              </option>
            </select>
        </div>
        <div class="uk-width-1-5@m">
            <select name="mark" class="ui fluid search normal dropdown">
              <option value="">Marque</option>
              <option v-for="mark in selectInput.marks" v-bind:value="mark.term_id" >
                {{ mark.name }}
              </option>
            </select>
        </div>
        <div class="uk-width-1-6@m uk-hidden">
            <select name="model" class="ui fluid normal dropdown">
                <option value="">Modèle</option>
                <option v-for="model in selectInput.models" v-bind:value="model.term_id" >
                  {{ model.name }}
                </option>
              </select>
        </div>
        <div class="uk-width-1-5@m">
            <select name="maxprice" class="ui fluid normal dropdown">
                <option value="">Prix maxi</option>
                <option v-for="(price, i) in selectInput.maxPrice" v-bind:class="{ 'lastNumber': (selectInput.maxPrice.length - 1) == i }" v-bind:value="price">
                  {{ price | euro }}
                </option>
              </select>
        </div>
        <div class="uk-width-1-5@m uk-hidden">
            <select name="fuel" class="ui fluid normal dropdown">
                <option value="">Carburant</option>
                <option v-for="fuel in selectInput.fuels" v-bind:value="fuel.term_id" >
                  {{ fuel.name }}
                </option>
              </select>
        </div>
        <div class="uk-width-1-5@m">
            <button class="uk-button uk-button-euromada er-button-search">CHERCHER</button>
        </div>
      </form>
    </div>
  </div>
</section>