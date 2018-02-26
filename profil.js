(function($) {
  /** On load document */
  var profilTestExist = function () {
    var exist = document.getElementById( "app-profil" );
    if (_.isNull(exist)) return false;
    return true;
  };

  if (profilTestExist) {
    new Vue({
      el: "#app-profil",
      data: {
        currentId: null,
        adverts: []
      },
      mounted: function () {
        this.adverts = _.concat(__adverts__);
        $('.basic.commande.modal').modal({
          closable: true
        });
        $('.basic.delete.modal').modal( {
          closable: true,
          onDeny: function() {
            return true;
          }
        });
      },
      methods: {
        voirCommande: function( $id ) {
          $('.basic.commande.modal').modal('show');
        },
        redirect: function( url ) {
          if (_.isNull(url)) return false;
          window.location.href = url;
        },
        deletePost: function( id ) {
          $('.basic.delete.modal').modal({
            onApprove: function() {
              var currentUrl = window.location.href;
              window.location.href = currentUrl + "?__post_delete_id=" + id + "&token=" + __user_token__;
              return true;
            }
          }).modal('show');
        },
        getOrderDetails: function() {
          return new Promise(function(resolve, reject) {

          }) 
        }
      }
    })
  }
})(jQuery)