
<script type="text/x-template" id="template-summary">
  <div class="er-summary">
    <h1 itemprop="name" class="er-h1">{{ product.title }}</h1>
    <div  itemprop="offers" itemscope itemtype="http://schema.org/Offer">
      <p class="label uk-margin-remove">Prix</p>
      <p class="price uk-margin-remove-top er-price">{{ product.cost | euro }}</p>

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
<!-- Platform social -->
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<!-- /end --> 

<script type="text/x-template" id="template-social">
  <div class="uk-margin-auto"> 
      
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

<script type="text/x-template" id="template-other-annonces">

  <div id="app-other-publisher" class="uk-margin-large-top">
    <div class="uk-flex"><h2 class="uk-text-uppercase uk-text-center uk-margin-auto er-h2 er-underline">Autres annonces</h2></div>
    <div class="uk-container uk-container-small uk-margin-top">
      <p class="uk-text-center uk-margin-auto uk-width-xlarge">Vous pouvez également consulter les sites web ci-dessous, il y a des millions des
      voitures à votre disposition. Communiquez-nous la référence que vous souhaitez commander et nous vous ferons un devis.</p>
      <div class="ui small images" >
        <div class="uk-inline er-other-sidebar-logo">
          <span class="website" data-url="https://www.leboncoin.fr/_vehicules_/offres/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/leboncoin.jpg" />
          </span>
        </div>
        <div class="uk-inline er-other-sidebar-logo">
          <span  class="website" data-url="https://www.automobile.fr/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/automobile.jpg" />
          </span>
        </div>
        <div class="uk-inline er-other-sidebar-logo">
          <span class="website" data-url="https://www.mobile.de/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/mobile.de.jpg" />
          </span>
        </div>
        <div class="uk-inline er-other-sidebar-logo">
          <span class="website" data-url="https://www.europe-camions.com/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/europe-camions.jpg" />
          </span>
        </div>
        <div class="uk-inline er-other-sidebar-logo">
          <span class="website" data-url="https://www.paruvendu.fr/voiture-occasion/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/paruvendu.jpg" />
          </span>
        </div>
        <div class="uk-inline er-other-sidebar-logo">
          <span class="website" data-url="https://www.lacentrale.fr/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/lacentrale.jpg" />
          </a>
        </div>
        <div class="uk-inline er-other-sidebar-logo">
          <span class="website" data-url="https://www.autoscout24.fr/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/auto_scout24.jpg" />
          </span>
        </div>
        <div class="uk-inline er-other-sidebar-logo">
          <span class="website" data-url="https://www.aramisauto.com/">
            <img class="uk-logo" src="<?= get_template_directory_uri() ?>/img/logo/aramisauto.jpg" />
          </span>
        </div>
      </div>
    </div>
  </div>
  
</script>
<script type="text/javascript">
  (function($) {
    $(document).ready(function() {
      $('span.website').each(function(index, element) {
        $( element )
        .css('cursor', 'pointer')
        .on('click', function() {
          var url = $( this ).data( 'url' );
          window.open( url, '_blank' );
        });
      });
    });
  })(jQuery)
</script>
