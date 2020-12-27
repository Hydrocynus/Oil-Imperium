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
   * @return string|array Ausgabe der Datenbank.
   */
  private function sqlSelect(string $sql, int $fetch_style = PDO::FETCH_BOTH, int $start = 0, $length = null) {
    if ($length == 0) return [];

    $stmt = $this->DB->query($sql);
    if (!$stmt) return false;

    $resp = $stmt->fetchAll($fetch_style);

    if (is_string($resp)) {
      return $resp;
    }
    if (is_array($resp)) {
      return array_slice($resp, $start, $length);
    }
    else {
      return json_encode($resp);
    }
  }

  /**
   * Fuehrt einen Insert Befehl durch.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string $table Name der betroffenen Tabelle.
   * @param string[] $cols Betroffene Spalten.
   * @param string[] $vals Einzufuegende Werte.
   * @return string|array Rueckgabe der Datenbank.
   */
  public function sqlInsert(string $table, array $cols, array $vals) {
    $table       = trim($table);

    $cols        = implode(", ", $cols);
    $cols        = trim($cols);

    $placeholder = array_map(function () { return '?'; }, $vals);
    $placeholder = implode(", ", $placeholder);

    $sql         = "INSERT INTO $table ($cols) VALUES ($placeholder)";
    $stmt        = $this->DB->prepare($sql);
    return         $stmt->execute($vals);
  }

  /**
   * Fuehrt einen Update Befehl durch.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string $table Name der betroffenen Tabelle.
   * @param string[] $cols Betroffene Spalten.
   * @param string[] $vals Einzufuegende Werte.
   * @param string $condition Bedingung, die Tupel erfuellen muessen, um vom Update betroffen zu sein.
   * @return string|array Rueckgabe der Datenbank.
   */
  public function sqlUpdate(string $table, array $cols, array $vals, string $condition = "") {
    $table = trim($table);

    $set  = [];
    $tmp  = [];
    $len  = count($cols);

    for ($i=0; $i<$len; $i++) {
      $set[]          = "$cols[$i] = :$cols[$i]";
      $tmp[$cols[$i]] = $vals[$i];
    }

    $set  = implode(", ", $set);
    $vals = $tmp;

    $condition = trim($condition);
    if (!empty($condition)) {
      $condition = "WHERE $condition";
    }

    $sql  = "UPDATE $table SET $set $condition";
    $stmt = $this->DB->prepare($sql);
    return  $stmt->execute($vals);
  }

  /**
   * Fuehrt einen Delete Befehl durch.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string $table Name der betroffenen Tabelle.
   * @param string $condition Bedingung, die Tupel erfuellen muessen, um geloescht zu werden.
   * @return string|array Rueckgabe der Datenbank.
   */
  public function sqlDelete(string $table, string $condition) {
    $table = trim($table);

    $condition = trim($condition);
    if (!empty($condition)) {
      $condition = "WHERE $condition";
    }

    $sql  = "DELETE FROM $table $condition";
    $stmt = $this->DB->prepare($sql);
    return  $stmt->execute();
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
  public function sqlSelectAssoc(string $select, int $start = 0, $length = null) : array {
    return $this->sqlSelect($select, PDO::FETCH_ASSOC, $start, $length);
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
  public function sqlSelectNum(string $select, int $start = 0, $length = null) : array {
    return $this->sqlSelect($select, PDO::FETCH_NUM, $start, $length);
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
  public function sqlSelectOneRow(string $select, int $start = 0) : array {
    $resp = $this->sqlSelectAssoc($select, $start, 1);
    if (count($resp) > 0) return $resp[0];
    else return [];
  }
}