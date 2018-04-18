<?php

final class Euromada_Login {
  /** This variable contain a fail login message */
  public static $message;
  public function __construct() {}
  
  /**
   * Get register page link
   * @return string - Url of register page
   */
  protected static function registerLink() {
    $register_page_id = get_option('register_page_id', false);
    return get_permalink( (int)$register_page_id );
  }

  /**
   * This function check if login is fail or not
   * @return bool
   */
  protected static function statusFail() {
    $failed = Services::getValue('login', false);
    if (false != $failed) {
      self::$message = "Échec de l'ouverture de session de l'utilisateur. Votre mot de passe n'est pas correct";
      return true;
    } else return false;
  }
  
  /**
   * [euromada_login] - This is an shorcode for login form
   * @param attrs - Attributs
   * @param content - Content of balise shortcode
   * @return void 
   */
  public static function render($attrs, $content = null) {
    $profil_page_id = get_option('profil_page_id', false);
    $args = [];
    $defaults = array(
      'echo' => true,
      'redirect' => get_the_permalink((int)$profil_page_id),
      'form_id' => 'loginform',
      'label_username' => __( 'Email Address' ),
      'label_password' => __( 'Password' ),
      'label_remember' => __( 'Remember Me' ),
      'label_log_in' => __( 'Log In' ),
      'id_username' => 'user_login',
      'id_password' => 'user_pass',
      'id_remember' => 'rememberme',
      'id_submit' => 'wp-submit',
      'remember' => true,
      'value_username' => '',
      // Set 'value_remember' to true to default the "Remember me" checkbox to checked.
      'value_remember' => true
    );
    $args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
    $login_form_top    = apply_filters( 'login_form_top', '', $args );
    $login_form_middle = apply_filters( 'login_form_middle', '', $args );
    $login_form_bottom = apply_filters( 'login_form_bottom', '', $args );

    $action = esc_url( site_url( 'wp-login.php', 'login_post' ) );
    ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
    <script type="text/javascript">
      var vCaptcha;
      var rCaptcha = false;
      /**
       * async function on recaptchaJs load
       */
      var onloadCallback = function() {
        vCaptcha = grecaptcha.render('reCaptcha', {
          'sitekey' : '<?= __site_key__ ?>',
          'callback': function(response ) {
            rCaptcha = response;
          }
        });
      };

      (function($){
        $(document).ready(function() {
          $('form#<?= $args['form_id'] ?>')
            .on('submit', function(event) {
              if (rCaptcha == false) event.preventDefault(); // return false
              return true;
            });

          $('form#<?= $args['form_id'] ?>')
          .form({
            on: 'blur',
            fields: {
              log: {
                identifier: 'log',
                rules: [
                  {
                    type   : 'empty',
                    prompt : 'Veillez definir le nom d\'utilisateur'
                  }
                ]
              },
              pwd: {
                identifier: "pwd",
                rules: [
                    {
                      type   : 'empty',
                      prompt : 'Veillez definir le mot de passe'
                    }
                ]
              }
            }
          });
        });
      })(jQuery)
      
    </script>
    <style type="text/css">
      form input {
        padding-left: 30px !important;
      }
    </style>
    <div class="uk-width-1-3@s uk-margin-auto">

    <?php if (self::statusFail()) : ?>
      <div class="ui negative message">
        <i class="close icon"></i>
        <div class="header">
          Erreur
        </div>
        <p><?= self::$message ?></p>
      </div>
    <?php endif; ?>

      <form class="ui form" name="<?= $args['form_id'] ?>" id="<?= $args['form_id'] ?>" action="<?= $action ?>" method="post">
          <?= $login_form_top ?>
        <h5 class="ui header">
          SE CONNECTER
          <div class="sub header">Compte EUROMADA</div>
        </h5>
        <div class="ui items">
          <div class="ui item corner labeled input">
            <input placeholder="Nom d'utilisateur" name="log" type="text">
            <!-- <div class="ui left corner label">
              <i class="user icon"></i>
            </div> -->
          </div>

          <div class="ui item corner labeled input">
            <input placeholder="Mot de passe" name="pwd" type="password">
            <!-- <div class="ui left corner label">
              <i class="asterisk icon"></i>
            </div> -->
          </div>
        </div>
          <?= $login_form_middle ?>
          <div id="reCaptcha"></div>
          <div class="uk-margin">
            <div class="uk-inline"><button type="submit" class="positive ui button"><?= $args['label_log_in'] ?></button></div>
          </div>

          <div class="uk-margin">
            <div class="uk-inline">
              <span >Vous n'avez pas de compte?</span>
              <a href="<?= self::registerLink() ?>"  class="uk-button uk-button-link" >Crée un compte</a>
            </div>
          </div>
          <div class="ui error message"></div>
          <?= $login_form_bottom ?>
      </form>
      </div>
    <?php
  }
}