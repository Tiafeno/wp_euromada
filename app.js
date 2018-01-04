(function () {
  /** On load document */
  function appExist( $id ) {
    var exist = document.getElementById( $id );
    if (_.isNull(exist)) return false;
    return true;
  }

  moment.locale("fr");

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
      routeLinkOrder: function (product_id) {
        /** redirect in order page  */

      }
    },
  });

  /** End */

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
        sorting: ""
      },
      mounted: function () {
        jQuery('.ui.dropdown').dropdown();
      },
      methods: {
        sortBy: function () {
          this.adverts = _.sortBy(this.adverts, this.sorting);
        },
        routeLinkProduct: function (id) {
          var link = "products.html?_id=";
          window.location.href = link + id;
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
      mounted: function () {
        if ( _.isUndefined(__advert__) ) console.warn("adverts variable is undefined");
        this.product = __advert__;
        window.setTimeout(() => {
          this.access = true;
          jQuery('.segment').dimmer('hide');
        }, 400);

      }
    });

})(window)

