<?php
class Barrel extends DBCnc {
  /**
   * Gibt die Spielinformation in Abhaengigkeit eines Codes aus.
   * @author Tobias
   * @version 27.12.2020
   * @since 02.12.2020
   * @param string $code Code eines Spiels.
   * @return array Informationen zu dem Spiel.
   */
  public function getGameInfoByCode(string $code) : array {
    $select = "SELECT spielcode, ip, port, letzte_aenderung FROM spiel where spielcode = '$code'";
    return $this->sqlSelectOneRow($select);
  }

  /**
   * Gibt Spielinformationen in Abhaengigkeit einer IP aus.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string $ip IP-Adresse.
   * @return array Inforamtionen zu Spielen.
   */
  public function getGameInfoByIP(string $ip) : array {
    $select = "SELECT spielcode, ip, port, letzte_aenderung FROM spiel where ip = '$ip'";
    return $this->sqlSelectAssoc($select);
  }

  /**
   * Fuegt in der Datenbank ein Spiel hinzu.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string $code Spielcode des neuen Spiels.
   * @param string $ip IP-Adresse des Websockets des neuen Spiels.
   * @param string $port Port des Websockets des neuen Spiels.
   * @return string|array Rueckgabe der Datenbank.
   */
  public function addGame(string $code, string $ip, string $port) {
    $table = "spiel";
    $cols = ["spielcode", "ip", "port"];
    $vals = [$code, $ip, $port];

    return $this->sqlInsert($table, $cols, $vals);
  }
}