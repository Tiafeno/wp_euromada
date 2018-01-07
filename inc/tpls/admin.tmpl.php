<div class="wrap">
  <h1>Euromada - Réglages généraux</h1>
  <form action="" method="POST">
    <?= wp_nonce_field('euromada_settings', 'euromada_settings_nonce') ?>
    <table class="form-table">
      <tbody>
        <?php while( list(, $form) = each($forms) ) : $content = (object) $form; ?>
          <tr>
            <th scope="row">
              <label for="blogname"><?= $content->blogname ?></label>
            </th>
            <td>
              <select name="<?= $content->id ?>" id="<?= $content->id ?>" >
                  <option value=""> </option>
                <?php for ($i = 0; $i < count($posts); $i++) : ?>
                  <option value="<?= $posts[ $i ]->ID ?>" <?php if ($posts[ $i ]->ID == (int)$content->page_id) echo 'selected' ?> > 
                    <?= $posts[ $i ]->post_title ?>
                  </option>
                <?php endfor; ?>
              </select>
              <p class="description"><?= $content->description ?></p>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications">
    </p>
  </form>
</div>