<?php 
class SocketClient {

  public $socket;
  public $id;
  public $header = array();
  public $handshake = false;
  //long msg Handling
  public $multipleFrames;
  public $partialBuffer; 
  public $partialMsg; 

  function __construct($id, $socket) {
    $this->id = $id;
    $this->socket = $socket;
  }
}