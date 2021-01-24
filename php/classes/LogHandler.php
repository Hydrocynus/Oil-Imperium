<?php 

class LogHandler {
  /**
   * //? Path angabe für log destination 
   * Schreibt neuen Logeintrag in .txt datei. 
   * @author Tim
   * @version 04.01.2021 
   * @since 29.11.2020
   * @param string $file Dateiname bzw Path der Logdatei.
   * @param string $txt neuer Logeintrag.
   */
  public static function writeLog($txt, $newLine = false, $file ="./logs/log.txt") {
    $newLog = "";
    if ($newLine) { $newLog = "\n";}
    $newLog .= date("Y.m.d H:i") . ": " . $txt . "\r\n";
    $logFile = @fopen($file, "a");
    fwrite($logFile, $newLog);
    fclose($logFile);
  }

  /** notw?
   * Liesst Logdatei aus. 
   * @author Tim
   * @version 03.01.2021 
   * @since 29.11.2020
   * @param string $file Dateiname bzw Path der Logdatei.
   * @return string $read Inhalt de Logdatei .
   */
  public static function readLog($file) {
    $logFile1 = fopen($file, "r");
    $read = fread($logFile1, filesize($file));
    fclose($logFile1);
    return $read;
  }
}