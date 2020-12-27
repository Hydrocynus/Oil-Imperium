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
  public function create_game() {
    $code = $this->generate_code();
    $ip   = $this->get_ip();
    $port = $this->generate_port($ip);

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
  private function generate_code() {
    do {
      $code = generate_random_string(4, OILIMP_UPPER_CASE_LETTERS);
    } while ($this->code_exists($code));
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
  private function code_exists(string $code) {
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
  private function get_ip() {
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
  private function generate_port(string $ip) {
    do {
      $port = generate_random_string(5, OILIMP_NUMBERS);
    } while (!$this->port_in_range($port) || !$this->port_available($port, $ip) || $this->port_exists($port, $ip));
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
  private function port_exists(string $port, string $ip) {
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
  private function port_available($port) {
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
  private function port_in_range($port) {
    return $port >= 49152 && $port <= 65535;
  }
}