<?php
class OilSocket extends Socket {
  function __construct($addr, $port) {
    parent::__construct($addr, $port);
  }

  protected function onMessage($user, $msg) {
      //$this->send($user, "MSG_FROM_Server");
      // foreach($this->users as $u) 
  }

  protected function onConnection($user) {
      // foreach($this->users as $u) 
      echo "\nnew Connection established";
      //$this->send($user, "conn Send");
  }

  protected function onClose($user) {
      // foreach($this->users as $u) 
      //    $this->send($u, "<div class='srv'>Client {$user->id} hat uns verlassen!</div>");
  }
}