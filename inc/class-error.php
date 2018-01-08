<?php

final class EuromadaError {
  public $message;
  public function __construct( &$error ) {
    $this->message = $error->data;
  }

  public function getMessage() {
    return $this->message;
  }
}