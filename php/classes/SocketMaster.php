<?php
class SocketMaster {
  /**
   * Startet ein neues WebSocket. duh!
   * @author Tobias Tim
   * @version 19.01.2021
   * @since 12/2020 - 01/2021
   * @param string $host Adresse des WebSockets.
   * @param string|number $port Port des WebSockets.
   * @return void
   */
  public static function startSocket(string $host, $port) {
    $connection = @fsockopen($host, $port);

    if (is_resource($connection)) {
        LogHandler::writeLog("Socket bereits offen");
        fclose($connection);
        return;
    }

    $oilSocket = new OilSocket($host, $port);
    try {
      $oilSocket->start();
    } catch (Exception $e) {
      LogHandler::writeLog("Socket konnte nicht gestartet werden: " . print_r($e, false));
    }
  }
}