<?php 

class WebSocketUser {

  public $socket;
  public $id;
  public $header = array();
  public $handshake = false;

  function __construct($id, $socket) {
    $this->id = $id;
    $this->socket = $socket;
  }
}