(function($) {
  /** On load document */
  var profilTestExist = function () {
    var exist = document.getElementById( "app-profil" );
    if (_.isNull(exist)) return false;
    return true;
  };

  if (profilTestExist) {
    var vm = new Vue({
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
        editPost: function( id ) {
          window.location.href = window.modificationUrl + "?post_id=" + id;
        },
        deletePost: function( id ) {
          var self = this;
          $('.basic.delete.modal').modal({
            onApprove: function() {
              var requestPromise = null;
              requestPromise = new Promise(function(resolve, reject) {
                $.ajax({
                  url: jParams.ajaxUrl,
                  type: 'GET',
                  dataType: 'json',
                  data: {
                    action           : 'ajx_action_delete_advert',
                    __post_delete_id : id,
                    token            : __user_token__
                  }
                }).done(function( data ) {
                  if (data.success) {
                    resolve(data.response.ID);
                  }
                }).fail(function(jqXHR, textStatus) {
                  reject(false);
                })
              });
              requestPromise.then(function(successMessage) {
                if (successMessage) {
                  self.adverts = _.reject(self.adverts, { id: parseInt(successMessage) });
                } else {
                  alert("Une erreur inconnue c'est produit. Veuillez reéssayer ulterieurement");
                }
              });
              
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