<?php

final class Euromada_register{
  public function __construct() {}
  public static function render($attrs, $content = null) {
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
              address: {
                identifier: 'address',
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
                identifier  : 'same_password',
                rules: [
                  {
                    type   : 'match[pwd]',
                    prompt : 'Please put the same value in both fields'
                  }
                ]
              }
            }
          });
        });
      })(jQuery)
      
    </script>
    <div class="main ui container">
      <form id="registerform" action="" method="POST" class="ui form">
      <?= wp_nonce_field('register', 'register_nonce') ?>
        <h4 class="ui dividing header">Shipping Information</h4>
        <div class=" fields">
          <div class="field"> <!-- error -->
            <label>First Name</label>
            <input placeholder="First Name" name="firstname" type="text">
          </div>
          <div class="field">
            <label>Last Name</label>
            <input placeholder="Last Name" name="lastname" type="text">
          </div>
        </div>

        <div class="field">
          <label>Address E-mail</label>
          <div class="fields">
            <div class="twelve wide field">
              <input type="text" name="email" placeholder="Address email">
            </div>
          </div>
        </div>

        <div class=" fields">
          <div class="field">
            <label>Billing Address</label>
            <input placeholder="Address" name="address" type="text">
          </div>
          <div class="field">
            <label>Phone</label>
            <input placeholder="Phone" name="phone" type="text">
          </div>
        </div>

        <h4 class="ui dividing header">login Information</h4>
        <div class="fields">
          <div class="field">
            <label>Username</label>
            <input type="text" name="username" placeholder="Username">
          </div>
        </div>
        <div class="fields">
          <div class="field">
            <label>Password</label>
            <input type="password" name="pwd" >
          </div>
          <div class="field">
            <label>Confirm Password</label>
            <input type="password" name="same_password">
          </div>
        </div>

        <button class="ui button" type="submit">S'inscrire</button>
      </form>
    </div>
    <?php
  }
}