<?php
require_once('Bitmask.php');
class Utils {

  /**
   * Generiert einen HTML-Button fuer eine form.
   * @author Tobias Tim
   * @version 28.11.2020 Bugfix (Tim)
   * @since 28.11.2020
   * @param string $action Schluesselattribut der Anfrage.
   * @param string $value Wert der Anfrage.
   * @param string $text Inhalt des Buttons.
   * @param string $type (Optional) Typ des Form-Buttons. Standard ist submit.
   * @param string $classes (Optional) Wenn angegeben, wird das class-Attribut ueberschrieben.
   * @param string $attr (Optional) Zusaetzliche Attribute koennen hier angegeben werden.
   * @return string Der generierte Button. duh!
   */
  public static function createButton(string $action, string $value, string $text, string $type = "submit", string $classes = null, string $attr = "") : string {
    if (!in_array($type, ['submit', 'reset', 'button'])) $type = "submit";

    if (!isset($classes)) $classes = "col-auto mx-1 btn-outline-light";

    return "<button type=\"$type\" class=\"btn $classes\" name=\"$action\" value=\"$value\" $attr>$text</button>";
  }

  /**
   * Generiert ein HTML-Input fuer eine form.
   * @author Tobias
   * @version 28.11.2020
   * @since 28.11.2020
   * @param string $action Schluesselattribut der Anfrage.
   * @param string $value Wert der Anfrage.
   * @param string $placeholder Platzhalter des Inputfeldes.
   * @param string $type (Optional) Typ des Inputfeldes. Standard ist text.
   * @param string $classes (Optional) Wenn angegeben, wird das class-Attribut ueberschrieben.
   * @param string $attr (Optional) Zusaetzliche Attribute koennen hier angegeben werden.
   * @return string Das generierte Input. duh!
   */
  public static function createInput(string $action, string $value, string $placeholder, string $type = "text", string $classes = null, string $attr = "") : string {
    if (!in_array($type, ['text','number','button','checkbox','color','date','datetime-local','email','file','hidden','image','month','number','password','radio','range','reset','search','submit','tel','text','time','url','week'])) $type = "text";

    if (!isset($classes)) $classes = "col-auto mx-1 btn-outline-light";

    return "<input type=\"$type\" class=\"btn $classes\" name=\"$action\" value=\"$value\" placeholder=\"$placeholder\" $attr>";
  }

  /**
   * Generiert einen String mit zufaelligen Zeichen.
   * @author Tobias
   * @version 27.12.2020
   * @since 02.12.2020
   * @param int $length Laenge des zu generierenden Strings.
   * @param int $type Bitmaske zur bestimmung der Zeichengruppe des generierten Strings:
   *                  - OILIMP_NUMBERS            Zahlen.
   *                  - OILIMP_LETTERS_UPPER_CASE Grossbuchstaben.
   *                  - OILIMP_LETTERS_LOWER_CASE Kleinbuchstaben.
   * @return string Generierter String.
   */
  public static function generateRandomString(int $length, int $type) : string {
    $chars = [];

    if ($type & OILIMP_NUMBERS) {
      $chars = array_merge($chars, ['0','1','2','3','4','5','6','7','8','9']);
    }
    if ($type & OILIMP_LETTERS_LOWER_CASE) {
      $chars = array_merge($chars, ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z']);
    }
    if ($type & OILIMP_LETTERS_UPPER_CASE) {
      $chars = array_merge($chars, ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']);
    }

    $chars = array_unique($chars);
    $lenChars = count($chars);
    $string = '';

    for ($length; $length>0; $length--) {
      $string .= $chars[random_int(0, $lenChars-1)];
    }

    return $string;
  }

  /**
   * Laed eine Datei Zeilenweise und trennt Zeilen an Doppelpunkten in ein assoziatives Array.
   * @author Tobias
   * @version 29.12.2020
   * @since 29.12.2020
   * @param string $path Pfad zur Datei.
   * @return array Assoziatives Arrays (Erster Eintrag einer Zeile in der Datei ist Schluessel, der Rest ist Wert).
   */
  public static function loadConfig(string $path) {
    $file   = file($path);
    $config = [];
    foreach ($file as $line) {
      if (empty(trim($line))) continue;
      $line             = explode(":", $line, 2);
      $line             = array_map('trim', $line);
      $config[$line[0]] = $line[1];
    }
    return $config;
  }

  /** 
   * XOR-Verknüpfung von zwei Strings.
   * Passt die länge der Maskierung an die Länge des Payloads an.
   * @author Tim
   * @version 08.12.2020 
   * @since 03.01.2021 
   * @param string payload Zu Verknüpende Folge
   * @param string mask Maskierung
   * @return string xor-verknüpfter String
   */
  function xorStr($payload, $mask) {
    $maskstr = "";
    
    while (strlen($maskstr) < strlen($payload)) {
      $maskstr .= $mask;
    }
    while (strlen($maskstr) > strlen($payload)) {
      $maskstr = substr($maskstr,0,-1);
    }

    return $maskstr ^ $payload;

  }

  /**  
   * Zeigt ein Frame leserlich in Binär an.
   * Für debuggung der Frames.
   * @author Tim
   * @version 08.12.2020 
   * @since 03.01.2021 
   * @param string frame welches Binär dargestellt werden soll
   * @return string binäre Darstellung eines Frames 
   */
  function getBinOfFrame($frame) {
    $bin = "";
    for ($i=0; $i < strlen($frame); $i ++ ) {
        $byte = substr($frame, $i);
        $byte = decbin(ord($byte));
        $zero = "";
    
        for ($j=strlen($byte); $j<8; $j++) {
            $zero .= "0". $zero;
        }
        $bin .= $zero . $byte;
    }
    return $bin;
  }
}