<?php
class OilSocket extends Socket {
  function __construct($addr, $port) {
    parent::__construct($addr, $port);
  }

  protected function onMessage($user, $msg) {
    
    try { $msg = json_decode($msg, true); }
    catch (Exception $e) { return ; }
    $cmd = $msg[0];
    $val = $msg[1];
    
    switch($cmd) {
      case "PING": 
        return;
      case "broadcast":
        $this->broadcastInstruction($cmd, $val);
        break;
      case "userList": 
        $this->userList();
        break;
      case "userChange":
      case "userAdd":
        $user->setUserInfo($val);
        $this->broadcastInstruction($cmd, $val);
        break;
      case "getPlayerlist": 
        $this->sendPlayerlist($user);
        break; 
      default: 
        LogHandler::writeLog("Server got: ". $val);
        LogHandler::writeLog("from: ". $user->id);
        break;
    }
    
    
  }

  protected function onConnection($user) {
    $info = $user->getUserInfo();
    $msg = json_encode(["userSelf", $info]);
    $this->send($user, $msg);
    $this->broadcastInstruction("userAdd", $info);
  }

  protected function onClose($user) {
    LogHandler::writeLog("onClose FEUER");
    $info = $user->getUserInfo();
    $this->broadcastInstruction("userRemove", $info["id"]);
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
  public function broadcastInstruction($cmd, $val) {
    $msg = json_encode([$cmd, $val]);
    $this->broadcast($msg);
  }
}