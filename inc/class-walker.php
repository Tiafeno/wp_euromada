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

class Primary_Walker extends Walker_Nav_Menu {

  public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul></div>{$n}";
	}

  public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
    $indent = str_repeat( $t, $depth );
    
		$classes = array( 'sub-menu', 'uk-nav', 'uk-dropdown-nav' );
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$output .= "{$n}{$indent}<div class=\"er-dropdown\" uk-dropdown=\"mode: click;\"><ul$class_names>{$n}";
	}

}


/** Offcanvas  */
class Primary_offcanvas_Walker extends Walker_Nav_Menu {
	var $db_fields = [
    'parent' => 'menu_item_parent',
    'id' => 'db_id'
  ];

  function start_el( &$output, $item, $depth = 0, $args = [], $id = 0) {
		if (in_array('current-menu-item', $item->classes)) {
			$item->classes[] = 'uk-active';
		}
    $output .= sprintf("\n <li class='%s'><a href='%s'>%s</a> \n", implode(' ', $item->classes), $item->url, $item->title );
  }

  public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

  public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
    $indent = str_repeat( $t, $depth );
    
		$classes = array( 'sub-menu', 'uk-nav-sub' );
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

}