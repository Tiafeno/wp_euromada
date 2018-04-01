<?php

final class Euromada_register
{
  public function __construct() {}
  public static function render($attrs, $content = "") {
    global $MESSAGE;
    $msgExist = is_null($MESSAGE) ? true : (is_object($MESSAGE) ? true : false);
    if ($msgExist) return true;
    /** Denied access if user is connected */
    $verification = is_user_logged_in() ? false : true;
    ?>
    <script type="text/javascript">
      (function($){
        $(document).ready(function() {
          $('.container form')
          .form({
            on: 'blur',
            fields: {
              firstname: {
                identifier: 'firstname',
                rules: [
                  {
                    type   : 'empty',
                    prompt : 'Veillez remplir ce champ'
                  }
                ]
              },
              lastname: {
                identifier: 'lastname',
                rules: [
                  {
                    type   : 'empty',
                    prompt : 'Veillez remplir ce champ'
                  }
                ]
              },
              adress: {
                identifier: 'adress',
                rules: [
                  {
                    type   : 'empty',
                    prompt : 'Veillez remplir ce champ'
                  }
                ]
              },
              username: {
                identifier: 'username',
                rules: [
                  {
                    type   : 'empty',
                    prompt : 'Veillez remplir ce champ'
                  }
                ]
              },
              email: {
                identifier  : 'email',
                rules: [
                  {
                    type   : 'email',
                    prompt : 'Please enter a valid e-mail'
                  }
                ]
              },
              pwd: {
                identifier: "pwd",
                rules: [
                    {
                      type   : 'empty',
                      prompt : 'Veillez remplir ce champ'
                    },
                    {
                      type   : 'minLength[6]',
                      prompt : 'Your password must be at least {ruleValue} characters'
                    }
                ]
              },
              password: {
                identifier  : 'same_pwd',
                rules: [
                  {
                    type   : 'match[pwd]',
                    prompt : 'Please put the same value in both fields'
                  }
                ]
              },
              terms: {
                identifier: 'terms',
                rules: [
                  {
                    type   : 'checked',
                    prompt : 'You must agree to the terms and conditions'
                  }
                ]
              }
            }
          });
        });
      })(jQuery)
      
    </script>
    <div class="main ui container">

  <?php if ($verification) : ?>
      <form id="registerform" action="" method="POST" class="ui form">
      <?= wp_nonce_field('register', 'register_nonce') ?>
        <h4 class="ui dividing header">Informations personnelles</h4>
        <div class="three fields">
          <div class="field"> 
            <label>Vous êtes ?</label>
            <select class="ui fluid search dropdown" name="type">
              <option value=""></option>
              <option value="seller">Vendeur</option>
              <option value="buyer">Acheteur</option>
            </select>
          </div>
          <div class="field"> <!-- error -->
            <label>Nom de famille</label>
            <input placeholder="" name="firstname" type="text">
          </div>
          <div class="field">
            <label>Prénom</label>
            <input placeholder="" name="lastname" type="text">
          </div>
        </div>

        <div class="three fields">
          <div class="field">
            <label>Adrèsse E-mail</label>
            <div class="fields">
              <div class="twelve wide field">
                <input type="text" name="email" placeholder="Adresse email">
              </div>
            </div>
          </div>
        </div>
        
        <div class="three fields">
          <div class="field">
            <label>Votre adresse</label>
            <input placeholder="Adresse" name="adress" type="text">
          </div>
          <div class="field">
            <label>Numéro de téléphone</label>
            <input placeholder="Votre numéro" name="phone" type="text">
          </div>
        </div>

        <h4 class="ui dividing header">Informations de connexion</h4>
        <div class="three fields">
          <div class="field">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" placeholder="">
          </div>
        </div>
        <div class="three fields">
          <div class="field">
            <label>Mot de passe</label>
            <input type="password" name="pwd" >
            <div class="ui black tiny message">
              Pour plus de sécurité, votre mot de passe doit contenir au moins 6 caractères.
            </div>
          </div>
          <div class="field">
            <label>Confirmez le mot de passe</label>
            <input type="password" name="same_pwd">
          </div>
        </div>
        <div class="fields">
          <div class="field">

            <div class="ui checkbox" style="margin-bottom: 10px">
              <input type="checkbox" name="newsletter">
              <label>Je souhaite recevoir des offres des partenaires du site euromada susceptibles de m'intéresser</label>
            </div>

            <div class="ui checkbox uk-margin-bottom">
              <input type="checkbox" name="terms">
              <label>J'accepte les <b>Conditions Générales de Vente</b></label>
            </div>

          </div>
        </div>

        <button class="ui blue button" type="submit">S'inscrire</button>
        <div class="ui error message"></div>
      </form>
    <?php else: ?>
      <p class="uk-margin-remove er-h2">Vous n'avez pas les autorisations pour afficher les contenues de cette page.</p>
      <p><?= $content ?></p>
    <?php endif; ?>
    </div>
    <?php
  }
}