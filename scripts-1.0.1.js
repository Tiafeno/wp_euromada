/** 
 * 
 * Libraries:
 * ** @lodash
 * ** @jQuery
*/
var oFile;
var previewUpload = null; // DOM Element
var noImage = jParams.templateUrl + "/img/gallery-add.png";

(function ($) {

  function formatNumber($value) {
    var v = parseFloat($value);
    if (isNaN(v)) return $value;
    return new Intl.NumberFormat('de-DE', {}).format(v);
  }

  $( document ).ready(function() {
    oFReader = new FileReader(), rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
    
    oFReader.onload = function (oFREvent) {
      var image = new Image();
      image.src = oFREvent.target.result;
      image.onload = function() {
        console.log( oFile.size );
      };
      previewUpload.attr('src', oFREvent.target.result);
      var stateValue = previewUpload.data('state');
      if (stateValue == 'not_uploaded') previewUpload.data('state', 'uploaded');
    };

    $('select[name="orderBy"]')
      .change(function () {
        $.ajax({
          url: jParams.ajaxUrl,
          contentType: "application/x-www-form-urlencoded",
          method: "POST",
          dataType: "json",
          data: {
            action: "ajx_action_order_review",
            order: $(this).val().trim()
          }
        })
          .done(function (response) {
            if (response.success) {
              window.location.reload();
            } else {
              console.log(response.msg);
            }
          })
          .fail(function (jqXHR, status) {
            console.warn("Probléme de connexion!, " + status);
          })
      });
    /**
     * Format all element with class `.money` a currency
     */
    var interv = window.setInterval(function () {
      var moneyElements = $( ".money" );
      var numTocurrency = $(".numTocurrency");

      $.each(numTocurrency, function (key, value) {
        $(value).text(function (index) {
          var numberValue = parseFloat($(this).text().trim());
          return formatNumber(numberValue);
        })
      });

      if (moneyElements.length === 0) return;
      $.each( moneyElements, function ( key, value ) {
        $( value ).text( function ( index ) {
          var currencyValue = parseFloat( $( this ).text().trim() );
          return new Intl.NumberFormat('de-DE', {
            style: "currency",
            currency: 'EUR'
          }).format( currencyValue );
        });
      });
      window.clearInterval( interv );

    }, 1500);
    
  });  // End document ready

  /** On load document */
  function appExist( $id ) {
    var exist = document.getElementById( $id );
    if (_.isNull(exist)) return false;
    return true;
  }

  function redirectTo(url) {
    window.location.href = url;
  }

  /** Directive */
  Vue.directive('upload', {
    bind: function (el, binding, vnode) {
      el.addEventListener('click', function (element) {
        var inputFile = $('#' + binding.value);
        previewUpload = $( el ).find('img');
        /** Fire click event handler */
        inputFile.trigger('click');
      });
    }
  });

  /** Filter and Component vue */

  Vue.filter('euro', function (value) {
    var v = parseFloat(value);
    if (isNaN(v)) return value;
    return new Intl.NumberFormat('de-DE', {
      style: "currency",
      minimumFractionDigits: 0,
      currency: 'EUR'
    }).format(v);
  });

  Vue.filter('money', function (value) {
    return formatNumber(value);
  });

  Vue.filter("moment", function (value) {
    moment.locale("fr");
    var currentDate = new Date( value );
    return moment(currentDate).format('LLLL'); 
  });

  Vue.filter('formatName', function ( value ) {
    var schema = [
      { slug: "mark", name: "Marque" },
      { slug: "model", name: "Modèle" },
      { slug: "model-year", name: "Année-modèle" },
      { slug: "fuel", name: "Carburant" },
      { slug: "gearbox", name: "Boite de vitesse" },
      { slug: "mileage", name: "Kilométrage" }
    ];
    var findElement = _.find(schema, { slug: value.trim() });
    return findElement == undefined ? value : findElement.name;
  })

  Vue.component('pagination', {
    template: '#template-pagination'
  });

  Vue.component('social-media', {
    template: '#template-social',
    props: ['product']
  });

  Vue.component('annonces', {
    template: '#template-other-annonces',
    props: []
  });

  Vue.component('psummary', {
    props: ['product'],
    template: '#template-summary',
    methods: {
      routeLinkOrder: function ( product_id ) {
        var currentUrl = window.location.href;
        window.location.href = currentUrl + "?order=" + parseInt( product_id );
      }
    },
  });

  /** End */
  if ( appExist("app-publish") )
    new Vue({
      el: "#app-publish",
      data: {
        imageNoUploaded: noImage,
        imageLimite: [0, 1, 2, 3],
        pictures: []
      },
      methods: {
        remove: function ( event, id ) {
          var el = event.target;
          var parent = $( el ).parents( '.ctn' );
          var imgPreview = parent.find( 'img.image' );
          //var status = imgPreview.data('state');
          imgPreview.data('state', "not_uploaded");
          imgPreview.attr('src', noImage);
          /** reset input value */
          $("#" + id).val("");
          /** hide trash button */
          var removeElement = $( el ).parents( '.ctn' ).find( '.er-remove-picture' )
          if ( ! removeElement.hasClass('uk-hidden'))
            removeElement.addClass('uk-hidden');
        }
      },
      mounted: function () {
        for (var i in this.imageLimite) {
          this.pictures = this.pictures.concat({
            identification: "image_" + i,
            positionIndex: i
          })
        }
      },
      updated: function() {
        var inputFiles = $('input[type=file].picture');
        /** Detecte change input file */
        inputFiles.each(function ( index, element ) {
          var input = $( element );
          var byte_to_16M = 16777216; // 16Mb
          var byte_to_12M = 12582912; // 12Mb
          input.change( function (e) {
            var currentElement = e.target;
            var identification = input.attr('id');
            if (document.querySelector('#' + identification).files.length === 0) { return; }
            oFile = document.querySelector('input[type=file]#' + identification).files[0];

            // Vérifier la taille du fichier
            if (oFile.size > byte_to_12M) { alert('Le fichier sélectionné est trop volumineux. La taille maximale est 12 Mo.'); return; }

            // Vérifier le type de fichier
            if ( ! rFilter.test(oFile.type)) { alert("You must select a valid image file!"); return; }

            // Active delete button 
            var removeElement = input.parents('.ctn').find('.er-remove-picture');
            if (removeElement.hasClass('uk-hidden'))
              removeElement.removeClass('uk-hidden');
              
            if (oFile)
              oFReader.readAsDataURL(oFile);
          });
        })
      }
    });

  if ( appExist("app-search") )
    new Vue({
      el: "#app-search",
      data: {
        selectInput: {
          marks: jParams.search.mark,
          models: jParams.search.model,
          fuels: jParams.search.fuel,
          categories: jParams.search.category,
          maxPrice: _.concat(__priceInterval__)
        }
      },
      mounted: function () {

      },
      methods: {

      }
    });
  if ( appExist("app-publisher") )
    new Vue({
      el: '#app-publisher',
      data: {
        cards: 3,
        adverts: _.chunk(__adverts__, 4)
      },
      methods: {
        onClick: function( url ) {
          window.location.href = url;
        }
      },
      mounted: function () {
        jQuery('.special.cards').find('.image').dimmer({
          on: 'hover'
        });
      }
    });

  if ( appExist("app-benefit") )
    new Vue({
      el: "#app-benefit",
      data: {
        benefits: [],
        rates: {}
      },
      methods: {
        __init__: function() {
          var bfts = [];
          if ( _.isUndefined(__categories__) ) console.warn("categories variable is undefined");
          _.forEach(__categories__, function( categorie ) {
            var content = {};
            if (_.isEmpty(categorie.desc)) return;
            var desc =  JSON.parse(categorie.desc) ;
            content.name = categorie.name;
            content.image = categorie.image;

            content.url = categorie.url;
            content.cost = desc.prix;
            
            bfts = _.concat( bfts, content );
          });
          this.benefits = _.orderBy( bfts, ['cost'], ['asc']);
          // console.log(this.benefits);
        },
        redirect: function ( url ) {
          window.location.href = url;
        }
      },
      created: function () {
        this.__init__();
        window.setTimeout(function() {
          $('#app-benefit').find('.image').dimmer({
            on: 'hover'
          });
        }, 1500);

      },
      mounted: function() {
        
      }
    });
  
  if ( appExist("app-lists") )
    new Vue({
      el: "#app-lists",
      data: {
        sorting: "",
        adverts: []
      },
      mounted: function () {
        jQuery('.ui.dropdown').dropdown();
        this.adverts = _.concat(_.map(__adverts__, function(value) {
          return value;
        }));
      },
      methods: {
        sortBy: function () {
          this.adverts = _.sortBy(__adverts__, [this.sorting]);
        }
      }
    });

  if (appExist('app-custom'))
    new Vue({
      el: '#app-custom',
      data: {}
    })

  if (appExist('app-promotion'))
    new Vue({
      el: "#app-promotion",
      data: {
        products: []
      }
    });

  if (appExist("app-product"))
    var vProduct = new Vue({
      data: {
        activeClass: 'active',
        product: {},
        access: false
      },
      el: "#app-product",
      beforeCreate: function () {
        jQuery('.segment')
          .dimmer({closable: false})
          .dimmer('show');
      },
      methods: {},
      updated: function () {

      },
      mounted: function () {
        var self = this;
        if (_.isUndefined(__advert__)) console.warn("adverts variable is undefined");
        this.product = __advert__;
        window.setTimeout(function () {
          this.access = true;
          console.log(self.product);
          jQuery('.segment').dimmer('hide');
          jQuery('.venobox').venobox({
            framewidth: '1000px'
          });
        }, 400);

      }
    });


})(jQuery)



