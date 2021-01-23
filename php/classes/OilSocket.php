<?php
class OilSocket extends Socket {
  function __construct($addr, $port) {
    parent::__construct($addr, $port);
  }

  protected function onMessage($user, $msg) {
    switch($msg) {
      case "PING": 
        return;
        break;
      case "del": 
        break;
      case "dis": 
        break;
      case "huhu": 
        LogHandler::writeLog("huhu");
        break;
      default: 
          LogHandler::writeLog("Server got: ". $msg);
          LogHandler::writeLog("from: ". $user->id);
      
          $this->broadcast($msg);
        break;
    }

    // try { $msg = json_decode($msg); }
    // catch (Exception $e) { return ; }
    // $cmd = $msg[0];
    // $msg  = $msg[1];
    // return broadcastInstruction($cmd, $msg);
    if ($msg == "del") {
      $this->deleteUser($user->socket);
    }
    if ($msg == "dis") {
      $this->disconnect($user->socket);
    }
  }

  protected function onConnection($user) {
      // foreach($this->users as $u) 
      //$this->send($user, "conn Send");
  }

  protected function onClose($user) {
      // foreach($this->users as $u) 
      //    $this->send($u, "<div class='srv'>Client {$user->id} hat uns verlassen!</div>");
  }

  /**
   * Sendet eine Anweisung an alle.
   * @author Tobias
   * @version 16.01.2021
   * @since 16.01.2021
   * @param string $cmd Befehl der Anweisung.
   * @param string $msg Wert der Anweisung.
   * @return void
   */
  public function broadcastInstruction($cmd, $msg) {
    $msg = json_encode([$cmd, $msg]);
    $this->broadcast($msg);
  }
}