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

<script type="text/x-template" id="template-pagination">
  <ul class="uk-pagination uk-flex-center" uk-margin>
    <li><a href="#"><span uk-pagination-previous></span></a></li>
    <li><a href="#">1</a></li>
    <li class="uk-disabled"><span>...</span></li>
    <li><a href="#">5</a></li>
    <li><a href="#">6</a></li>
    <li class="uk-active"><span>7</span></li>
    <li><a href="#">8</a></li>
    <li><a href="#"><span uk-pagination-next></span></a></li>
  </ul>
</script>
