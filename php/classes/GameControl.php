<?php
require_once('Utils.php');
class GameControl extends Barrel {

  /**
   * Erstellt ein neues Spiel.
   * Gneriert Code, IP und Port.
   * Speichert in Datenbank.
   * @author Tobias
   * @version 27.12.2020
   * @since 02.12.2020
   * @return string Code des neu erstellten Spiels.
   */
  public function createGame() {
    $code = $this->generateCode();
    $ip   = $this->getIp();
    $port = $this->generatePort($ip);

    // in DB speichern

    return "$code [$ip:$port]";

    // return $code;
  }

  /**
   * Generiert einen neuen Spielcode.
   * Generiert nur neue Codes.
   * @author Tobias
   * @version 02.12.2020
   * @since 02.12.2020
   * @return string Gnerierter Code.
   */
  private function generateCode() {
    do {
      $code = generateRandomString(4, OILIMP_LETTERS_UPPER_CASE);
    } while ($this->codeExists($code));
    return $code;
  }

  /**
   * Prueft, ob der Code bereits in der Datenbank existiert.
   * @author Tobias
   * @version 02.12.2020
   * @since 02.12.2020
   * @param string $code Code, der ueberprueft werden soll.
   * @return bool true, wenn der Code bereits existiert.
   */
  private function codeExists(string $code) {
    $resp = $this->getGameInfoByCode($code);
    return isset($resp['ip']) && isset($resp['port']) && isset($game['spielcode']);
  }

  /**
   * Bezieht die aktuelle Server-IP
   * Zukuenftig Unterstuetzung fuer mehrere Server moeglich.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @return string IP
   */
  private function getIp() {
    return gethostbyname(gethostname());
  }

  /**
   * Generiert einen neuen Port fuer eine IP.
   * Generiert nur neue Ports.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @return string Gnerierter Port.
   */
  private function generatePort(string $ip) {
    do {
      $port = generateRandomString(5, OILIMP_NUMBERS);
    } while (!$this->portInRange($port) || !$this->portAvailable($port, $ip) || $this->portExists($port, $ip));
    return $port;
  }

  /**
   * Prueft, ob der Port zusammen mit der IP bereits in der Datenbank existiert.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string $port Port, der ueberprueft werden soll.
   * @param string $ip IP, mit der der Port in Verbindung steht.
   * @return bool true, wenn der Port bereits existiert.
   */
  private function portExists(string $port, string $ip) {
    $resp = $this->getGameInfoByIP($ip);
    foreach ($resp as $game) {
      if (isset($game['ip']) && isset($game['port']) && isset($game['spielcode'])) {
        return true;
      }
    }
    return false;
  }

  /**
   * Prueft, ob ein Port verfuegbar ist.
   * Muss spaeter ersetzt werden, wenn meherere Server moeglich sind. (Ueberpruefung muss dann auf dem jeweiligen Server laufen.)
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string|int $port Zu ueberpruefender Port.
   * @return bool true, wenn Port verfuegbar ist.
   */
  private function portAvailable($port) {
    $socket = @fsockopen("localhost", $port, $errno, $errstr, 0.1);
    if (!$socket) {
        return true;
    } else {
        fclose($socket);
        return false;
    }
  }

  /**
   * Prueft, ob der Port im zulaessigen Bereich der dynamischen Ports liegt.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @param string|int $port Zu ueberpruefender Port.
   * @return bool true, wenn der Port im gueltigen Bereich liegt.
   */
  private function portInRange($port) {
    return $port >= 49152 && $port <= 65535;
  }
}