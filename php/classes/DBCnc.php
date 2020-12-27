<?php
class DBCnc {
  private $DB;

  public function __construct()
  {
    $dsn = 'mysql:host=localhost;dbname=barrel';
    $this->DB = new PDO($dsn, 'root');
  }

  /**
   * Fuehrt einen SQL Befehl aus und gibt das Ergebnis zurueck.
   * @author Tobias
   * @version 02.12.2020
   * @since 02.12.2020
   * @param string $sql SQL-String.
   * @param int $fetch_style Art der Rueckgabe. Siehe hierzu Dekumentation von PDO::fetch.
   * @param int $start (Optional) Startzeile der Abfrage. (Standard: 1. Zeile)
   * @param null|int $length (Optional) Anzahl Zeilen in der Abfrage. (Wenn ausgelassen, werden alle Zeilen zurueckgegeben.)
   * 
   */
  private function execSQL(string $sql, int $fetch_style = PDO::FETCH_BOTH, int $start = 0, $length = null) {
    if ($length == 0) return [];

    $stmt = $this->DB->query($sql);
    if (!$stmt) return false;

    $resp = $stmt->fetchAll($fetch_style);
    return array_slice($resp, $start, $length);
  }

  public function sqlInsert() {

  }

  public function sqlUpdate() {

  }

  public function sqlDelete() {

  }

  /**
   * 
   * @author Tobias
   * @version 02.12.2020
   * @since 02.12.2020
   * @param string $select SELECT-String.
   * @param int $start (Optional) Startzeile der Abfrage. (Standard: 1. Zeile)
   * @param null|int $length (Optional) Anzahl Zeilen in der Abfrage. (Wenn ausgelassen, werden alle Zeilen zurueckgegeben.)
   * @return array Nummerisches Array pro Zeile mit assozietiven Arrays der Spalten.
   */
  public function sqlSelectAssoc(string $select, int $start = 0, $length = null) {
    return $this->execSQL($select, PDO::FETCH_ASSOC, $start, $length);
  }

  /**
   * 
   * @author Tobias
   * @version 02.12.2020
   * @since 02.12.2020
   * @param string $select SELECT-String.
   * @param int $start (Optional) Startzeile der Abfrage. (Standard: 1. Zeile)
   * @param null|int $length (Optional) Anzahl Zeilen in der Abfrage. (Wenn ausgelassen, werden alle Zeilen zurueckgegeben.)
   * @return array Nummerisches Array pro Zeile mit nummerischen Arrays der Spalten.
   */
  public function sqlSelectNum(string $select, int $start = 0, $length = null) {
    return $this->execSQL($select, PDO::FETCH_NUM, $start, $length);
  }

  /**
   * 
   * @author Tobias
   * @version 02.12.2020
   * @since 02.12.2020
   * @param string $select SELECT-String.
   * @param int $start (Optional) Startzeile der Abfrage. (Standard: 1. Zeile)
   * @return array Assoziatives Array der Zeile.
   */
  public function sqlSelectOneRow(string $select, int $start = 0) {
    $resp = $this->sqlSelectAssoc($select, $start, 1);
    if (count($resp) > 0) return $resp[0];
    else return [];
  }
}