<?php
class OilSocket extends Socket {
  function __construct($addr, $port) {
    parent::__construct($addr, $port);
  }

  protected function onMessage($user, $msg) {
    
    try { $msg = json_decode($msg); }
    catch (Exception $e) { return ; }
    $cmd = $msg[0];
    $msg = $msg[1];
    
    switch($cmd) {
      case "PING": 
        return;
      case "TEST":
        $this->broadcastInstruction($cmd, $msg);
        break;
      case "LogUser": 
        $this->userList();
        break;
      case "userChange":
      case "userAdd":
        $user->setUser($msg);
        $this->broadcastInstruction($cmd, $msg);
        break;
      case "getPlayerlist": 
        LogHandler::writeLog("pre LIST");
        $this->sendPlayerlist($user);
        break; 
      default: 
        LogHandler::writeLog("Server got: ". $msg);
        LogHandler::writeLog("from: ". $user->id);
        break;
    }
    
    
  }

  protected function onConnection($user) {
    $info = $user->getUserInfo();
    $msg = json_encode(["userSelf", $info]);
    $this->send($user, $msg);
    $this->broadcastInstruction("newUser", $info) ;
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