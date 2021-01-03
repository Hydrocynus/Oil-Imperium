<?php
class SocketMaster {
  public static function startSocket(string $host, $port) {
    $oilSocket = new OilSocket($host, $port);
    try {
      $oilSocket->start();
    } catch (Exception $e) {
      print_r($e);
    }
  }
}