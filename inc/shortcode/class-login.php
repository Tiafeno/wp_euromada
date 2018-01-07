<?php

final class Euromada_Login {
  public function __construct() {}
  
  /**
   * [euromada_login] - This is an shorcode for login form
   * @param attrs - Attributs
   * @param content - Content of balise shortcode
   * @return void 
   */
  public static function render($attrs, $content = null) {
    $args = [];
		$defaults = array(
			'echo' => true,
			'redirect' => \get_permalink(),
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
      <form name="<?= $args['form_id'] ?>" id="<?= $args['form_id'] ?>" action="<?= $action ?>" method="post">

          <?= $login_form_top ?>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: user"></span>
                  <input class="uk-input" name="log" type="text">
              </div>
          </div>
          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: lock"></span>
                  <input class="uk-input" name="pwd" type="password">
              </div>
          </div>
          <?= $login_form_middle ?>

          <div class="uk-margin">
            <div class="uk-inline"><button type="submit" class="uk-button uk-button-primary"><?= $args['label_log_in'] ?></button></div>
          </div>

          <div class="uk-margin">
            <div class="uk-inline">
              <span style="display: block;">Vous n'avez pas de compte?</span>
              <button style="display: block" class="uk-button uk-button-link" >Crée un compte</button>
            </div>
          </div>
          
          <?= $login_form_bottom ?>
      </form>
    <?php
  }
}