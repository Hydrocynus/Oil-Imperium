<?php 
  
/**
 * //? Path angabe für log destination 
 * Schreibt neuen Logeintrag in .txt datei. 
 * @author Tim
 * @version 29.12.2020 
 * @since XX.12.2020
 * @param string $file Dateiname bzw Path der Logdatei.
 * @param string $txt neuer Logeintrag.
 */
function writeLogFile($file, $txt, $err = false) {
  $txt = @readLogFile($file) . $txt;
  $logFile = @fopen($file, "w");
  fwrite($logFile,date("Y.m.d H:i") . ": ".  $txt."\n");
  fclose($logFile);
}

/**
 * Liesst Logdatei aus. 
 * @author Tim
 * @version 29.12.2020 
 * @since XX.12.2020
 * @param string $file Dateiname bzw Path der Logdatei.
 * @return string $read Inhalt de Logdatei .
 */
function readLogFile($file) {
  $logFile1 = fopen($file, "r");
  $read = fread($logFile1, filesize($file));
  fclose($logFile1);
  return $read;
}