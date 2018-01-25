var oFile;
var previewUpload = null; // DOM Element
var noImage = jParams.templateUrl + "/img/gallery-add.png";

(function ($) {
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
    /**
     * Format all element with class `.money` a currency
     */
    var interv = window.setInterval(function(){
      var moneyElements = $( ".money" );
      if (_.isEmpty(moneyElements)) return;

      $.each( moneyElements, function( key, value ) {
        $( value ).text( function( index ) {
          var currencyValue = parseFloat( $( this ).text().trim() );
          return new Intl.NumberFormat('de-DE', {
            style: "currency",
            currency: 'MGA'
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

  /**
   * Remove input value,
   * Set data status to 'not_uploaded'
   * Set src to imageNoUploaded value
   */
  function removeImage() {

  }

  /** Directive */
  Vue.directive('upload', {
    bind: function (el, binding, vnode) {
      el.addEventListener('click', element => {
        var inputFile = $('#' + binding.value);
        previewUpload = $( el ).find('img');
        /** Fire click event handler */
        inputFile.trigger('click');
      });
    }
  });

  /** Filter and Component vue */

  Vue.filter('ariary', function (value) {
    var v = parseFloat(value);
    if (isNaN(v)) return value;
    return new Intl.NumberFormat('de-DE', {
      style: "currency",
      currency: 'MGA'
    }).format(v);
  });

  Vue.filter('money', function (value) {
    var v = parseFloat(value);
    if (isNaN(v)) return value;
    return new Intl.NumberFormat('de-DE', {
    }).format(v);
  });

  Vue.filter("moment", function(value) {
    moment.locale("fr");
    var currentDate = new Date( value );
    return moment(currentDate).startOf('hour').fromNow(); 
  });

  Vue.filter('formatName', function (value) {
    var schema = [
      { slug: "mark", name: "Marque" },
      { slug: "model", name: "Modèle" },
      { slug: "model-year", name: "Année-modèle" },
      { slug: "fuel", name: "Carburant" },
      { slug: "gearbox", name: "Boite de vitesse" },
    ];
    var findElement = _.find(schema, { slug: value.trim() });
    return findElement.name;
  })

  Vue.component('pagination', {
    template: '#template-pagination'
  });

  Vue.component('social-media', {
    template: '#template-social',
    props: ['product']
  });

  Vue.component('psummary', {
    props: ['product'],
    template: '#template-summary',
    methods: {
      routeLinkOrder: function( product_id ) {
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
        remove: function( event, id ) {
          var el = event.target;
          var parent = jQuery( el ).parents( '.ctn' );
          var imgPreview = parent.find( 'img.image' );
          //var status = imgPreview.data('state');
          imgPreview.data('state', "not_uploaded");
          imgPreview.attr('src', noImage);
          /** reset input value */
          jQuery("#" + id).val("");
          /** hide trash button */
          var removeElement = jQuery( el ).parents( '.ctn' ).find( '.er-remove-picture' )
          if ( ! removeElement.hasClass('uk-hidden'))
            removeElement.addClass('uk-hidden');
        }
      },
      mounted: function() {
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
        inputFiles.each(function(index, element ) {
          var input = $( element );
          input.change( e => {
            var currentElement = e.target;
            /*** Active delete button */
            var removeElement = input.parents('.ctn').find('.er-remove-picture');
            if (removeElement.hasClass('uk-hidden'))
              removeElement.removeClass('uk-hidden');
            
            var identification = input.attr('id');
            if (document.querySelector('#' + identification).files.length === 0) { return; }
            oFile = document.querySelector('input[type=file]#' + identification).files[0];
            if ( ! rFilter.test(oFile.type)) { alert("You must select a valid image file!"); return; }
            if (oFile)
              oFReader.readAsDataURL(oFile);
          });
        })
      }
    });

  

  var priceInterval = [
    1000000, 1500000,
    2000000, 2500000,
    3000000, 3500000,
    4000000, 4500000,
  ];
  if ( appExist("app-search") )
    new Vue({
      el: "#app-search",
      data: {
        selectInput: {
          marks: jParams.search.mark,
          models: jParams.search.model,
          fuels: jParams.search.fuel,
          categories: jParams.search.category,
          maxPrice: _.concat(priceInterval)
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
        benefits: [{
          title: "Voiture légère",
          cost: 2000000,
          link: jParams.templateUrl + "/img/benefits/01.jpg"
        },
        {
          title: "SPRINTER",
          cost: 10000000,
          link: jParams.templateUrl + "/img/benefits/02.jpg"
        },
        {
          title: "Camion poids louds",
          cost: 20000000,
          link: jParams.templateUrl + "/img/benefits/03.jpg"
        }
        ]
      }
    });
  
  if ( appExist("app-lists") )
    new Vue({
      el: "#app-lists",
      data: {
        sorting: "",
        postCount: 0,
        adverts: []
      },
      mounted: function () {
        jQuery('.ui.dropdown').dropdown();
        this.adverts = _.concat(__adverts__);
        this.postCount = this.adverts.length;
      },
      methods: {
        sortBy: function () {
          this.adverts = _.sortBy(__adverts__, [this.sorting]);
        }
      }
    });

  var router = ( ! _.isUndefined(window.VueRouter) ) ? new VueRouter({
    mode: 'history',
    routes: []
  }) : false;
  if ( router && appExist("app-product") )
    var vProduct = new Vue({
      router,
      data: {
        activeClass: 'active',
        product: {},
        access: false
      },
      el: "#app-product",
      beforeCreate: function () {
        jQuery('.segment')
          .dimmer({ closable: false })
          .dimmer('show');
      },
      methods : {
        
      },
      mounted: function () {
        if ( _.isUndefined(__advert__) ) console.warn("adverts variable is undefined");
        this.product = __advert__;
        window.setTimeout(() => {
          this.access = true;
          jQuery('.segment').dimmer('hide');
        }, 400);

      }
    });


})(jQuery)


