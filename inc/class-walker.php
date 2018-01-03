<?php
class Account_Walker extends Walker_Nav_Menu {
  var $db_fields = [
    'parent' => 'menu_item_parent',
    'id' => 'db_id'
  ];

  function start_el( &$output, $item, $depth = 0, $args = [], $id = 0) {
    $output .= sprintf("\n <li><a href='%s'><span class='er-user-icon' uk-icon='icon: %s'>".
                "</span><span class='er-user-text'>%s</span></a> \n", $item->url, $item->classes[0], $item->title );
  }
}