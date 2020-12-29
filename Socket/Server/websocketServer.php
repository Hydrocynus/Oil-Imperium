<?php 

require_once("./logHandler.php");
require_once("./websocketUser.php");

abstract class websocketServer {
  
  protected $users = [];
  protected $sockets = []; // Über Client aufrufen
  protected $master;
  protected $storage = [];

  function __construct($addr, $port) {
    $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);
    socket_bind($this->master, $addr, $port);
    socket_listen($this->master);
    $this->sockets["master"] = $this->master;
  }

  abstract protected function onConnection($user);
  abstract protected function onMessage($user, $msg);
  abstract protected function onClose($user);

  /** ? Sockets in Users speichern 
   * Gibt User vom zugehörigen socket. 
   * @author Tim
   * @version 08.12.2020 
   * @since XX.12.2020
   * @param object Socket Object.
   * @return object User Object.
   */
  protected function getUserBySocket($socket) {
    foreach($this->users as $user) {
      if ($user->socket == $socket) return $user;
    }
    return null;
  }

  /**
   * Hauptfunktion des Servers.
   * Endlosloop handelt:
   * -neue Connections 
   * -nachrichten von Usern
   * -disconnections
   * @author Tim
   * @version 03.12.2020 
   * @since XX.12.2020
   */
  function start() {
    echo "\n\n\n\n------------------------------------------------------------";
    while(true) {

      $write = $except = null;
      $read = $this->sockets;
      @socket_select($read, $write, $except, 1); 
      
      foreach($read as $socket) {
        echo "\nsocket: " . $socket;
        //Master Socket
        if ($socket == $this->master) {
          //onConnection
          $newReq = @socket_accept($socket);
          if ($newReq >= 0) { $this->connect($newReq); }
        } 
        else {//Client Handling
          //Client recv
          $recv = @socket_recv($socket, $buf, "2048", 0);//!
          echo "\nrecv: ". $recv; //!

          if ($recv === false) { //socket error
            // "Line 40... add Error Handling"
            echo "Socket Fail";
          } 
          else if ($recv == 0) { //connection lost handling
             $this->disconnect($socket); 
          }
          else { //socket handling
            //handshake
            $u = $this->getUserBySocket($socket);//! sockets in user bauen

            if (!$u->handshake) {
              //Respond to Handshake
              $tmp = str_replace("\r", "", $buf);
              if (strpos($tmp, "\n\n") === false) { continue; } 
              $this->handshake($u, $buf);
            } 
            else { 
              //recieve Data 
              echo "\nbuf: ".$buf;
              $data = $this->deframe($buf);
              //$this->send($u, $data["pl"]);
              // echo "\n Selected User: ". $u->id;
              // $this->read($u, $buf) //!
            }
          }
        }
      }
    }
  }

  /**
   * Wird aufgerufen wenn ein Client ein handshake request sendet.
   * Legt neuen User im users-Array an.
   * Legt neuen socket im socket-Array an: //!
   * @author Tim
   * @version 03.12.2020 
   * @since XX.12.2020
   * @param object Socket Object das sich mit dem master-Socket Verbindet. 
   */
  function connect($socket) {
    $newUser = new WebSocketUser(uniqid(), $socket);
    $this->users[$newUser->id] = $newUser;
    $this->sockets[$newUser->id] = $socket;
  }

  /**
   * Entfernt User aus users Array an.
   * Entfernt Socket aus Socket Array an: //!
   * schliesst socket
   * @author Tim
   * @version 03.12.2020 
   * @since XX.12.2020
   * @param object Socket Object das sich mit dem master-Socket Verbindet. 
   */
  function disconnect($socket) {
    $oldUser = $this->getUserBySocket($socket);

    if ($oldUser === null) return;

    unset($this->users[$oldUser->id]);
    unset($this->sockets[$oldUser->id]);

    $this->onClose($oldUser);
    socket_close($oldUser->socket);

    // $msg = $this->frame('', $oldUser, 'close'); //!
    // @socket_write($oldUser->socket, $msg, strlen($msg));
  }

  /**
   * Führt Handshake durch.
   * Prüft Request-Header/Client-Handshake 
   * Falls Invalid: Entfernt User, welcher sich verbinden will. 
   * Falls Valid: Sendet Response-Header/Server-Handshake an Client und User wird  mit Server verbunden.
   * => Websocketverbindung aufgebaut.
   * @author Tim
   * @version 03.12.2020 
   * @since XX.12.2020
   * @param object user User, welcher sich Verbinden will.
   * @param string buf Request Header.
   */
  function handshake($user, $buf) {
    $GUID = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";
    $header = [];
    $lines = explode("\n", $buf);

    foreach($lines as $line) {
      if (strpos($line, ":") !== false) {
        $tmp = explode(":", $line, 2);
        $index = strtolower(trim($tmp[0]));
        $header[$index] = trim($tmp[1]);

      } else if (stripos($line,"get ") !== false) {  //! 
        preg_match("/GET (.*) HTTP/i", $buf, $matches);
        $header["get"] = trim($matches[1]);
      }
    }

    if (!isset($header["get"])) $resp = "HTTP/1.1 405 Method Not Allowed\r\n\r\n";
    if (!isset($header["host"])) $resp = "HTTP/1.1 400 Bad Request";
    if (!isset($header["upgrade"])) $resp = "HTTP/1.1 400 Bad Request";
    if (!isset($header["connection"])) $resp = "HTTP/1.1 400 Bad Request";
    if (!isset($header["sec-websocket-key"])) $resp = "HTTP/1.1 400 Bad Request";
    if (!isset($header["sec-websocket-version"])) $resp = "HTTP/1.1 426 Upgrade Required\r\nSec-WebSocketVersion: 13";
    //! erwitern?
    // if (!isset($header["origin"])) $resp = "HTTP/1.1 426 Upgrade Required\r\nSec-WebSocketVersion: 13";
    // if (!isset($header["sec-websocket-protocol"])) $resp = "HTTP/1.1 426 Upgrade Required\r\nSec-WebSocketVersion: 13";
    // if (!isset($header["sec-websocket-extensions"])) $resp = "HTTP/1.1 426 Upgrade Required\r\nSec-WebSocketVersion: 13";
    
    if (isset($resp)) {
      socket_write($user->socket, $resp, strlen($resp));
      $this->disconnect($user->socket);
      return;
    }

    $user->header = $header;
    $user->handshake = $buf;

    // $wsKey = sha1($header["sec-websocket-key"]. $GUID);

    // $token = "";
    // for ($i=0; $i<20; $i++) $token .= chr(hexdec(substr($wsKey, $i*2, 2))); //! 
    // $token = base64_encode($token) ."\n\n";
    $wsKey = $header["sec-websocket-key"]; 
    $token = $wsKey.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
    $token = base64_encode(sha1($token, true));

    $resp = "HTTP/1.1 101 Switching Protocols\r\n";
    $upgrade = "Upgrade: websocket\r\n";
    $conn = "Connection: Upgrade\r\n";
    $wsToken = "Sec-WebSocket-Accept: $token\r\n\r\n";
    $resp = $resp.$upgrade.$conn.$wsToken;


 
    // $this->console("Generating Sec-WebSocket-Accept key...");
    // $acceptKey = $key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
    // $acceptKey = base64_encode(sha1($acceptKey, true));

    // $upgrade = "HTTP/1.1 101 Switching Protocols\r\n".
    //            "Upgrade: websocket\r\n".
    //            "Connection: Upgrade\r\n".
    //            "Sec-WebSocket-Accept: $acceptKey".
    //            "\r\n\r\n";

    socket_write($user->socket, $resp, strlen($resp));
    $this->onConnection($user);
  }

//--Datentransfer

  /**
   * Extrahiert header eines Websocket-Frames in ein Array. 
   * @author Tim
   * @version 08.12.2020 
   * @since XX.12.2020
   * @param string Frame.
   * @return array header des Frames. 
   * info: https://tools.ietf.org/html/rfc6455#section-5 
   */
  function getHeaderOfFrame($frame) {
    //frame in Binär //! weg machen 
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
    echo "\n\nbin: " . $bin . "\nlength: " . strlen($bin) . "\npayload length: " . (strlen($bin) - 64). " \n"; // info//!

    $header =  [
        //1. Byte
        "fin"     => $frame[0] & chr(128),
        "rsv1"    => $frame[0] & chr(64),
        "rsv2"    => $frame[0] & chr(32),
        "rsv3"    => $frame[0] & chr(16),
        "opcode"  => ord($frame[0]) & 15,//bindec(substr($bin, 4,4)),
        //2. Byte
        "hasmask" => $frame[1] & chr(128),

        "length"  => (ord($frame[1]) >= 128) ? ord($frame[1]) - 128 : ord($frame[1]),//bindec(substr($bin, 9,7)),//!
        "mask"    => "",//substr($frame, 2,4),//substr($bin, 16,32),
    ];

    //length holen und maske festlegen
    if ($header['length'] == 126) {
      if ($header['hasmask']) {
        $header['mask'] = $frame[4] . $frame[5] . $frame[6] . $frame[7];
      }
      $header['length'] = ord($frame[2]) * 256 + ord($frame[3]);
    } elseif ($header['length'] == 127) {
      if ($header['hasmask']) {
        $header['mask'] = $frame[10] . $frame[11] . $frame[12] . $frame[13];
      }
      $header['length'] = ord($frame[2]) * 65536 * 65536 * 65536 * 256 
                + ord($frame[3]) * 65536 * 65536 * 65536
                + ord($frame[4]) * 65536 * 65536 * 256
                + ord($frame[5]) * 65536 * 65536
                + ord($frame[6]) * 65536 * 256
                + ord($frame[7]) * 65536 
                + ord($frame[8]) * 256
                + ord($frame[9]);
    } elseif ($header['hasmask']) {
      $header['mask'] = $frame[2] . $frame[3] . $frame[4] . $frame[5];
    }

    return $header; 
  }

  /**
   * Verarbeitet frame/buffer aus einem socket. 
   * Extrahiert Header und Payload und verarbeitet die Informationen.
   * Header daten in binär außer opcode(int) und length(int).
   * @author Tim
   * @version 08.12.2020 
   * @since XX.12.2020
   * @param object user User, welcher sich Verbinden will.
   * @param string buf Request Header.
   * @return array data Daten eines Frames als Array.
   */
  function deframe($frame) {
    $header = $this->getHeaderOfFrame($frame);
    $payload = substr($frame, 6, $header["length"]); //length > 126 !
    $payload = $this->xorStr($payload, $header["mask"]);
    //Header Verarbeiten 
    //check rsv in Header todo 
    if (ord($header['rsv1']) + ord($header['rsv2']) + ord($header['rsv3']) > 0) {
      //$this->disconnect($user); // todo: fail connection
      return false;
    }
    //opcode Verarbeiten todo
    switch($header["opcode"]) {
      case 0:
      case 1:
        //msg
      case 2:
        //binary-data
        break;
      case 8:
        // todo: close the connection
        echo "\nCLOSE CONN opcode 8!";
        return "";
      case 9: //is ping frame
        //send pong frame w/ exact same payload data as ping frame 
        echo "\ntodo frame()";
        $reply = $this->frame($payload,$user,'pong'); //todo frame
        socket_write($user->socket,$reply,strlen($reply));
        return false;
      case 10://is pong frame ? 
        break;
      default:
        //$this->disconnect($user); // todo: fail connection
        return false;
        break;
    }
 
    //!
    echo "\npayload: " .$payload;
    echo "\nopcode: ". $header["opcode"];


    $data["payload"] = $payload;
    $data["header"] = $header;

    // echo "\n\n Data: \n"; //!
    // var_dump($data);

    return $data;
  }

  /**
   * Sendet etwas an einen User oder
   * schreibt alternativ die nachrricht in den Storage,
   * falls dieser momentan nicht Conneted ist.
   * @author Tim
   * @version 08.12.2020 
   * @since XX.12.2020
   * @param object user Object.
   * @param string msg zu Versendende Nachrricht.
   */
  function send($user, $msg) {
    if ($user->handshake) {
      //!$msg = $this->frame($msg, $user);
      socket_write($user->socket, $msg, strlen($msg));
    } else {
      $this->storage[] = ["user" => $user, "msg" => $msg];
    }
  }

  //todo send 
  //-an alle
  //-an ausgewählte

  /**utils
   * XOR-Verknüpfung von zwei Strings.
   * Passt die länge der Maskierung an die Länge des Payloads an.
   * @author Tim
   * @version 08.12.2020 
   * @since XX.12.2020
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
  
  //? 
  function frame() {

  }

}
