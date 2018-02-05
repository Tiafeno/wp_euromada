<div class="wrap">
  <h1>Euromada - Réglages généraux</h1>
  <form class="ui form" action="" method="POST">
    <?= wp_nonce_field('euromada_settings', 'euromada_settings_nonce') ?>

    <?php while( list(, $_form) = each($forms) ) : $form = (object) $_form; 
            if ($form->type == "select")
              $value = (false != $form->page_id) ? $form->page_id : "";
    ?>
    <div class="two fields">
      <div class="field"> 
        <label><?= $form->blogname ?></label>

      <?php if ($form->type == "select"): ?>
          <div class="ui selection dropdown">
            <div class="default text"><?= $form->blogname ?></div>
            <i class="dropdown icon"></i>
            <input name="<?= $form->name ?>" id="<?= $form->name ?>" value="<?= $value ?>" type="hidden">
            <div class="menu">
            <?php foreach ($posts as $post) : ?>
              <div class="item" data-value="<?= $post->ID ?>"><?= $post->post_title ?></div>
            <?php endforeach; ?>
            </div>
          </div>

          <?php if ( ! empty($form->description)): ?>
            <div class="ui blue tiny message">
              <?= $form->description ?>
            </div>
          <?php endif; ?>
      <?php endif; ?>

      <?php if ($form->type == "input"): ?>
        <input name="<?= $form->name ?>" value="<?= $form->value ?>" type="text" autocomplete="off">
      <?php endif; ?>

      </div>
    </div>
    <?php endwhile; ?>

    <p class="submit">
      <input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications">
    </p>
  </form>
</div>