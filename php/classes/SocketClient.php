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

  function __construct($id, &$socket) { //! gameId
    $this->id = $id;
    $this->socket = $socket;
    $this->lastPing = time();
  }

  /**
   * Setzt den User unset.
   * Entfernt das socket, 
   * entfernt die Handshake informationen und 
   * setzt lastPing auf null.
   * @author Tim
   * @version 23.01.2021
   * @since 23.01.2020
   * @return void
   */
  function unsetUser() {
    $this->socket = null;
    $this->header = null;
    $this->handshake = null;
    $this->lastPing = null;
  }

  /** @todo handshake stuff
   * Setzt das Socket des Users
   *  und setzt den lastPing. 
   * @author Tim
   * @version 23.01.2021
   * @since 23.01.2020
   * @return void
   */
  function setUser(&$socket) {
    $this->socket = $socket; 
    $this->lastPing = time(); 
  }
}