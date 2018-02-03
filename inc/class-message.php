<?php

final class EM_Message {
  protected $message;
  protected $title;
  public $type;

  public function __construct( $message = '', $title = 'Information', $type = 'info'){
    $this->message = &$message;
    $this->title = &$title;
    $this->type = &$type;
  }

  public function get_message() {
    return $this->message;
  }

  public function get_title() {
    return $this->title;
  }
}