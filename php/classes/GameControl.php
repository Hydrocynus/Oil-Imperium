<?php
require_once('Utils.php');
class GameControl extends Barrel {
  public $createGameError;

  /**
   * Erstellt ein neues Spiel.
   * Gneriert Code, IP und Port.
   * Speichert in Datenbank.
   * @author Tobias
   * @version 28.12.2020
   * @since 02.12.2020
   * @return string Code des neu erstellten Spiels.
   */
  public function createGame() : string {
    $code = $this->generateCode();
    $ip   = $this->getIp();
    $port = $this->generatePort($ip);
    $resp = $this->addGame($code, $ip, $port);

    if ($resp === true) {
      $this->createGameError = "";
      return $code;
    } else {
      $this->createGameError = $resp;
      return false;
    }
  }

  /**
   * Generiert einen neuen Spielcode.
   * Generiert nur neue Codes.
   * @author Tobias
   * @version 02.12.2020
   * @since 02.12.2020
   * @return string Gnerierter Code.
   */
  private function generateCode() : string {
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
  private function codeExists(string $code) : bool {
    $resp = $this->getGameInfoByCode($code);
    return isset($resp['ip']) && isset($resp['port']) && isset($resp['spielcode']);
  }

  /**
   * Bezieht die aktuelle Server-IP
   * Zukuenftig Unterstuetzung fuer mehrere Server moeglich.
   * @author Tobias
   * @version 27.12.2020
   * @since 27.12.2020
   * @return string IP
   */
  private function getIp() : string {
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
  private function generatePort(string $ip) : string {
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
  private function portExists(string $port, string $ip) : bool {
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
  private function portAvailable($port) : bool {
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
  private function portInRange($port) : bool {
    return $port >= 49152 && $port <= 65535;
  }

  /**
   * Leitet zur Lobby eines Spielcodes weiter.
   * @author Tobias
   * @version 29.12.2020 (LocalStorage fuer Spielderdaten.)
   * @since 27.12.2020
   * @param string $code Spielcode.
   * @return void
   */
  public function joinGame(string $code) : void {
    if (!$this->codeExists($code)) {
      return;
    }

    $game = $this->getGameInfoByCode($code);
    $ip   = $game['ip'];
    $port = $game['port'];

    $script = "<script>
                localStorage.setItem('code', '$code');
                localStorage.setItem('ip',   '$ip');
                localStorage.setItem('port', '$port');
                if (!localStorage.getItem('$code')) localStorage.setItem('$code', '{}');
                location = '../game/lobby.php';
              </script>";
    echo $script;
  }
}