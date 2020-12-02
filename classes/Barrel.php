<?php
set_include_path("../");
require_once('DBCnc.php');
class Barrel extends DBCnc {
  function getGameInfoByCode(string $code) {
    $select = "SELECT ip, port, letzte_aenderung FROM spiel where spielcode = '$code'";
    return $this->sqlSelectOneRow($select);
  }
}