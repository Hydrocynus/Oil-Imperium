<?php 
class SocketClient {

  public $id;
  public $gameId;

  //client socket
  public $socket;
  public $header = array();
  public $handshake = false;
  public $lastPing; 

  //message Fragmentation
  public $multipleFrames;
  public $partialBuffer; 
  public $partialMsg; 

  function __construct($id, $socket) { //! gameId
    $this->id = $id;
    $this->socket = $socket;
    $this->lastPing = time();
  }
}