<?php 
class SocketClient {

  public $id;
  public $name; 
  public $color; 
  public $ready; 

  //client socket
  public $socket;
  public $header = array();
  public $handshake = false;
  public $lastPing; 


  function __construct($id, &$socket) { 
    $this->id = $id;
    $this->socket = $socket;
    $this->lastPing = time();
    $this->name = "Pedda";
    $this->color = "#420420";
    $this->ready = "";
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

  /** 
   * Gibt User Infos.
   * @author Tim
   * @version 23.01.2021
   * @since 23.01.2020
   * @return array info | id, name, color, ready vom User
   */
  function getUserInfo() {
    $info = []; 
    $info["id"] = $this->id;
    $info["name"] = $this->name;
    $info["color"] = $this->color; 
    $info["ready"] = $this->ready;

    return $info; 
  }

  function setUserInfo($a) {
    if ($a["name"] !== null)  $this->name = $a["name"]; 
    if ($a["color"] !== null)  $this->color = $a["color"]; 
    if ($a["ready"] !== null)  $this->ready = $a["ready"]; 
  }

  function userChange($value) {
    foreach ($value as $k => $v) {
      switch ($k) {
        case "id": break;
        case "name":  $this->setUserInfo( $v); break;
        case "color": $this->setUserInfo( null, $v); break;
        case "ready": $this->setUserInfo( null, null, $v); break;
      }
    }
  }
}