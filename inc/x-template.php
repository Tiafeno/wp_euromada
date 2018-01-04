<script type="text/x-template" id="template-summary">
  <div class="er-summary">
    <h1 itemprop="name" class="er-h1">{{ product.title }}</h1>
    <div  itemprop="offers" itemscope itemtype="http://schema.org/Offer">
      <p class="label uk-margin-remove">Prix</p>
      <p class="price uk-margin-remove-top er-price">{{ product.cost | ariary }}</p>

      <div @click="routeLinkOrder(product.id)" class="ui button er-button-voir uk-margin-auto" style="display: table">
        Commander
        <span uk-icon="icon: chevron-right"></span>
      </div>

      <meta itemprop="price" content="14.90">
      <meta itemprop="priceCurrency" content="EUR">
      <meta itemprop="url" content="#url">
      <link itemprop="availability" href="http://schema.org/InStock">
    </div>
  </div>
</script>

<script type="text/x-template" id="template-social">
  <div class="uk-margin-auto"> 
      <a href="" class="uk-icon-button er-icon-button " uk-icon="icon: twitter"></a>
      <a href="" class="uk-icon-button er-icon-button " uk-icon="icon: facebook"></a>
      <a href="" class="uk-icon-button er-icon-button" uk-icon="icon: google-plus"></a>
  </div>
</script>

<style type="text/css">
  .er-price {
    font-size: 15pt;
    font-weight: bold;
    display: block;
    width: 100%;
  }

  /** override css style */

  .ui.primary.buttons .button,
  .ui.primary.button {
    background-color: #001689 !important;
  }

  .er-button-voir:hover {
    background-color: #f6bf11 !important;
    color: #000000 !important;
  }

  .er-button-voir {
    background-color: #001689 !important;
    font-size: 14px !important;
    border-radius: 0 !important;
    color: #ffffff !important;
  }

  .er-button-voir i {
    visibility: hidden;
  }

  .er-button-voir:hover i {
    visibility: visible
  }

  .er-sidebar section .extra.content .ui.button {
    padding: 11px !important;
  }

  .summary {
    background: #E5E5E5;
    padding-left: 15px;
    padding-right: 15px;
    padding-top: 25px;
    padding-bottom: 30px;
  }

  .ui.celled.table tr th,
  .ui.celled.table tr td {
    border-left: none !important;
  }

  .ui.table {
    border: none !important;
  }

  tr.er-product-specification {
    background-color: #001689;
    color: aliceblue;
  }
  
  
  .er-Exo {
    font-family: "Exo", sans-serif;
  }

  .er-icon-button {
    border-radius: 0px !important;
  }

  .content.er-share-title p {
    color: #ffffff;
  }

  .content.er-share-title {
    padding: 10px 20px;
    background-color: #000000;
  }

  .er-card .meta {
    padding-top: 10px;
  }

  .er-share-content {
    border-bottom: 1px solid #DAD0C6;
    padding-bottom: 9px;
    border-left: 1px solid #DAD0C6;
    border-right: 1px solid #DAD0C6;
  }
</style>