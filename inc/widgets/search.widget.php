<?php

class search_Widget extends \WP_Widget
{
  public function __construct() {
    parent::__construct("search_euromada", "Euromada > Search", array('description' => ''));
  }
  public function widget($args, $instance) {
    echo $args['before_widget'];
    include get_template_directory() . '/inc/tpls/search.tmpl.php';
    echo $args['after_widget'];
  }
  public function form($instance) {}
}
