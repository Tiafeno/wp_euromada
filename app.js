(function(){
    /** On load document */

    moment.locale("fr");
    var adverts = [
      {
        id: 1,
        title: "Mercedes ml 270 cdi",
        cost: 27500000,
        countPic: 7,
        description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas suscipit orci porta quam scelerisque " +
        "elementum. Donec orci nisi, pulvinar vitae mollis eu, finibus quis justo. Orci varius natoque penatibus et magnis dis" + 
        "parturient montes, nascetur ridiculus mus. Sed vitae tortor vel diam lobortis dignissim. Maecenas varius ante sit amet "+ 
        "ex ornare congue. Fusce ac lorem hendrerit, egestas tortor quis, venenatis felis. Ut vitae ipsum et nisl tristique tempor.",
        dateadd: moment().startOf('day').fromNow(),
        link: jParams.templateUrl + '/img/products/auto.png',
        gallery: [
          'img/products/auto1.png',
          'img/products/auto2.png',
          'img/products/auto3.png'
        ],
        attributes : {
          Mark: "Mercedes",
          Model: "Classe M",
          modelYear: "2002",
          Mileage: "213000",
          Fuel: "Diesel",
          GearBox: "Automatique"
        }
      },
      {
        id: 2,
        title: "TCe 90 Energy Intens",
        cost: 13000000,
        countPic: 2,
        description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas suscipit orci porta quam scelerisque " +
        "elementum. Donec orci nisi, pulvinar vitae mollis eu, finibus quis justo. Orci varius natoque penatibus et magnis dis" + 
        "parturient montes, nascetur ridiculus mus. Sed vitae tortor vel diam lobortis dignissim. Maecenas varius ante sit amet "+ 
        "ex ornare congue. Fusce ac lorem hendrerit, egestas tortor quis, venenatis felis. Ut vitae ipsum et nisl tristique tempor.",
        dateadd: moment().startOf('hour').fromNow(),
        link: jParams.templateUrl + '/img/products/auto1.png',
        gallery: [
          'img/products/auto.png',
          'img/products/auto2.png',
          'img/products/auto3.png'
        ],
        attributes : {
          Mark: "Mercedes",
          Model: "Classe B",
          modelYear: "2000",
          Mileage: "213000",
          Fuel: "Diesel",
          GearBox: "Automatique"
        }
      },
      {
        id: 3,
        title: "Land Rover Range",
        cost: 12490000,
        countPic: 4,
        description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas suscipit orci porta quam scelerisque " +
        "elementum. Donec orci nisi, pulvinar vitae mollis eu, finibus quis justo. Orci varius natoque penatibus et magnis dis" + 
        "parturient montes, nascetur ridiculus mus. Sed vitae tortor vel diam lobortis dignissim. Maecenas varius ante sit amet "+ 
        "ex ornare congue. Fusce ac lorem hendrerit, egestas tortor quis, venenatis felis. Ut vitae ipsum et nisl tristique tempor.",
        dateadd: moment().subtract(3, 'days').calendar(),
        link: jParams.templateUrl + '/img/products/auto2.png',
        gallery: [
          'img/products/auto1.png',
          jParams.templateUrl + '/img/products/auto2.png',
          jParams.templateUrl + '/img/products/auto3.png'
        ],
        attributes : {
          Mark: "Rover",
          Model: "Classe B",
          modelYear: "2000",
          Mileage: "213000",
          Fuel: "Diesel",
          GearBox: "Automatique"
        }
      },
      {
        id: 4,
        title: "Renault Captur",
        cost: 1509000,
        countPic: 1,
        dateadd: moment().subtract(6, 'days').calendar(),
        link: jParams.templateUrl + '/img/products/auto3.png'
      }
    ];
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
        routeLinkOrder: function(product_id) {
          /** redirect in order page  */
          console.log(product_id);
        }
      },
    });
  
    /** End */
    
  
    new Vue({
      el: "#app-search",
      data: {
        selectInput: {
          marks: jParams.search.mark,
          models: jParams.search.model,
          fuels: jParams.search.fuel,
          categories: jParams.search.category,
          maxPrice: []
        }
      },
      mounted: function() {
        console.log(this.selectInput.mark);
        var min_price = 1500000;
        var limit = 900000000;
        var ret = [];
        for (var i = min_price; i < limit; i += min_price) 
          this.selectInput.maxPrice = _.concat(this.selectInput.maxPrice, i);
      },
      methods: {
        
      }
    });
  
    new Vue({
      el: '#app-publisher',
      data: {
        itemI: _.sortBy(adverts, 'title'),
        itemII: _.sortBy(adverts, 'cost'),
        itemIII: _.sortBy(adverts, 'link'),
      },
      methods: {
        routeLinkView: function(product_id) {
          var link = "products.html?_id=";
          window.location.href = link + product_id;
        }
      },
      mounted: function () {
        jQuery('.special.cards').find('.image').dimmer({
          on: 'hover'
        });
      }
    });
  
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
  
    new Vue({
      el: "#app-lists",
      data: {
        sorting: "",
        adverts
      },
      mounted: function() {
        jQuery( '.ui.dropdown' ).dropdown();
      },
      methods: {
        sortBy: function() {
          this.adverts = _.sortBy(this.adverts, this.sorting);
        },
        routeLinkProduct: function(id) {
          var link = "products.html?_id=";
          window.location.href = link + id;
        }
      }
    });
  
  
    var router = ( ! _.isUndefined(window.VueRouter)) ? new VueRouter({
      mode: 'history',
      routes: []
    }) : false;
    if (router)
      var vProduct = new Vue({
        router,
        data: {
          product: {},
          access: false
        },
        el: "#app-product",
        beforeCreate: function() {
          jQuery('.segment')
            .dimmer({
              closable: false
            })
            .dimmer('show');
        },
        mounted: function() {
          var prt = this.$route.query._id;
          var product_id = parseInt( prt );
          if (isNaN( product_id )) return;
          this.product = _.findWhere(adverts, {id: product_id});
          window.setTimeout(() => {
            this.access = true;
            jQuery('.segment').dimmer('hide');
          }, 400);
          
        }
      });
  
})(window)

