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
  public function getGameInfoByCode(string $code) {
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
  public function getGameInfoByIP(string $ip) {
    $select = "SELECT spielcode, ip, port, letzte_aenderung FROM spiel where ip = '$ip'";
    return $this->sqlSelectAssoc($select);
  }
}