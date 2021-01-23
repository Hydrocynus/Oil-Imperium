<?php 
abstract class Socket {
  protected $users = [];
  protected $sockets = []; // Über Client aufrufen
  protected $master;
  protected $storage = []; // nicht eingebunden 

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
   * @since 03.12.2020
   */
  function start() {
    LogHandler::writeLog("Server started");

    while(true) {
      LogHandler::writeLog("is alive--------------------");

      //Heartbeat 
      foreach($this->sockets as $socket) {

        if ($socket == $this->master) { continue; }
        $u = $this->getUserBySocket($socket);
        LogHandler::writeLog($socket);
        LogHandler::writeLog("heartbeat: " . (time() - $u->lastPing));
        if (time() -  $u->lastPing > 30 ) {
          LogHandler::writeLog("send ping");
          $u->lastPing = time();
          $this->ping($u); 
        }
      } 

      //Handle Sockets 
      $write = $except = null;
      $read = $this->sockets;
      $sel = socket_select($read, $write, $except, 1);
            
      foreach($read as $socket) {
        //Master Socket
        if ($socket == $this->master) {
          //onConnection
          $newReq = @socket_accept($socket);
          if ($newReq >= 0) { $this->connect($newReq); }
        } 
        else {//Client Handling
          //Client recv
          $recv = @socket_recv($socket, $buf, "2048", 0);
          LogHandler::writeLog("recv: ". $recv);

          if ($recv === false) { //socket error
            
            $sockErrNo = socket_last_error($socket);
            LogHandler::writeLog("Socket Error: ". $sockErrNo . "|". socket_strerror($sockErrNo));
            $this->disconnect($socket); 
          } 
          else if ($recv == 0) { //connection lost handling
            LogHandler::writeLog("connection Lost");
            $this->disconnect($socket); 
          }
          else { //client socket handling

            $u = $this->getUserBySocket($socket);
            if (!$u->handshake) { //Respond to Handshake
              $tmp = str_replace("\r", "", $buf);
              if (strpos($tmp, "\n\n") === false) { continue; } 
              $this->handshake($u, $buf);
            } 
            else {             
              //recieve data
              $data = $this->deframe($u, $buf);
              LogHandler::writeLog("recv data: ". $data["payload"]);
              // $this->broadcast($data["payload"]);
              $this->onMessage($u, $data["payload"]);
            }
          }
        }
      }
    }

  }

  /** @todo user bereits vorhanden prüfen
   * Wird aufgerufen wenn ein Client ein handshake request sendet.
   * Legt neuen User im users-Array an.
   * Legt neuen socket im socket-Array an: //!
   * @author Tim
   * @version 03.12.2020 
   * @since 03.12.2020
   * @param object Socket Object das sich mit dem master-Socket Verbindet. 
   */
  function connect($socket) {
    $newUser = new SocketClient(uniqid(), $socket);
    $this->users[$newUser->id] = $newUser;
    $this->sockets[$newUser->id] = $socket;
    
    LogHandler::writeLog("Connect: ". $newUser->id);
    //$this->userList();
  }

  /**
   * Entfernt Socket aus Socket Array.
   * Schliesst Socket. 
   * @author Tim
   * @version 23.01.2021 
   * @since 03.12.2020
   * @param object Socket Object, welches entfernt werden soll
   */
  function disconnect(&$socket) {
    $oldUser = $this->getUserBySocket($socket);
    unset($socket);
    
    if ($oldUser === null) return;

    unset($this->sockets[$oldUser->id]);
    $this->users[$oldUser->id]->unsetUser();

    $this->onClose($oldUser);
    socket_close($oldUser->socket);

    LogHandler::writeLog("Disconnect user: ". $oldUser->id);
    // $msg = $this->frame('', $oldUser, 'close'); //!
    // @socket_write($oldUser->socket, $msg, strlen($msg));
  }

  /** 
   * Führt disconnect() aus.
   * -> Entfernt socket connection
   * Entfernt User aus users Array an.
   * Entfernt Socket aus Socket Array an.
   * @author Tim
   * @version 23.01.2021
   * @since 03.12.2020
   * @param object Socket Object, welcher user gelöscht werden soll. 
   */
  function deleteUser($socket) {
    $oldUser = $this->getUserBySocket($socket);
    $this->disconnect($socket);

    if ($oldUser === null) return;

    unset($this->users[$oldUser->id]);

    LogHandler::writeLog("DELETE user: ". $oldUser->id);
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
   * @since 03.12.2020
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

  /** //!
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
  function deframe(&$user, $frame) {
    $header = $this->getHeaderOfFrame($frame);
    $payload = substr($frame, 6, $header["length"]); //length > 126 !
    $payload = Utils::xorStr($payload, $header["mask"]);

    $data = []; 
    $data["payload"] = $payload;
    $data["header"] = $header;

    LogHandler::writeLog("data pl: ". $data["payload"]);
    LogHandler::writeLog("Opcode: ". $header["opcode"]);
    // echo "\n\n Data: \n"; //!
    // var_dump($data);

    return $data; //!todo
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
        //close the connection
        LogHandler::writeLog("closing Frame");
        $this->disconnect($user);
        return "";
      case 9: //is ping frame
        //send pong frame w/ exact same payload data as ping frame 
        LogHandler::writeLog("ping frame");

        $reply = $this->frame($payload,$user,'pong'); //todo frame
        socket_write($user->socket,$reply,strlen($reply));
        return false;
      case 10://!is pong frame  
        break;
      default:
        //$this->disconnect($user); // todo: fail connection
        return false;
        break;
    }
 
    //!
    LogHandler::writeLog("deframed: " .$payload);
    LogHandler::writeLog("opcode: ". $header["opcode"]);

    //mutiple Frames Handling | Frame > 2048 bits
    if ($user->multipleFrames) {
      $frame = $user->partialBuffer . $frame;
      $user->multipleFrames = false;
      return $this->deframe($user, $frame);
    }

    //extrahierte Daten vorranstellen 
    //$payload = $user->partialMsg . $payload; //!

    //solange länger
    if ($header['length'] > strlen(Utils::xorStr($payload, $header["mask"]))) {
      $user->multipleFrames = true;
      $user->partialBuffer = $frame;
      return false;
    }

    $payload = Utils::xorStr($payload, $header["mask"]);

    if ($header['fin']) {
      $user->partialMgs = "";
      return $payload;
    }

    $user->partialMsg = $payload;
    return false;
    
    //dec
    $data["payload"] = $payload;
    $data["header"] = $header;

    // echo "\n\n Data: \n"; //!
    // var_dump($data);

    return $data;
  }

  /**
   * Baut Frame aus gegebenen Payload ,um für die Kommunikation verwendet werden zu können.
   * Maskierung nicht unterstützt.
   * @author Tim
   * @version 04.01.2021 
   * @since XX.12.2020
   * @param string msg Payload des Frames.
   * @param string msgType gibt den Opcode des Frames an.
   * @param boolean msgFragment gibt an ob frame teil von mehreren Frames ist.
   * @return string frame welches nun verwendet werden kann.
   * info: https://tools.ietf.org/html/rfc6455#section-5
   */
  function frame($msg, $msgType='text', $msgFragment=false) {
    //set opcode
    switch ($msgType) {
      case 'continuous':
        $oc = 0;
        break;
      case 'text':
        $oc = 1;
        break;
      case 'binary':
        $oc = 2;
        break;
      case 'close':
        $oc = 8;
        break;
      case 'ping':
        $oc = 9;
        break;
      case 'pong':
        $oc = 10;
        break;
    }
    //check if frame is part of multiple Frames
    if (!$msgFragment) {
      $oc += 128;
    } 
    
    //get Length of payload
    $length = strlen($msg);
    $lengthField = "";
    if ($length < 126) {
      $plLength = $length;
    } 
    elseif ($length < 65536) {
      $plLength = 126;
      $hexLength = dechex($length);
      //$this->stdout("Hex Length: $hexLength");
      if (strlen($hexLength)%2 == 1) {
        $hexLength = '0' . $hexLength;
      } 
      $n = strlen($hexLength) - 2;

      for ($i = $n; $i >= 0; $i=$i-2) {
        $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
      }
      while (strlen($lengthField) < 2) {
        $lengthField = chr(0) . $lengthField;
      }
    } 
    else {
      $plLength = 127;
      $hexLength = dechex($length);
      if (strlen($hexLength)%2 == 1) {
        $hexLength = '0' . $hexLength;
      } 
      $n = strlen($hexLength) - 2;

      for ($i = $n; $i >= 0; $i=$i-2) {
        $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
      }
      while (strlen($lengthField) < 8) {
        $lengthField = chr(0) . $lengthField;
      }
    }

    //baut frame zusammen
    return chr($oc) . chr($plLength) . $lengthField . $msg;
  }

  /**
   * Sendet etwas an einen User oder
   * schreibt alternativ die nachrricht in den Storage,
   * falls dieser momentan nicht Conneted ist.
   * @author Tim
   * @version 08.12.2020 
   * @since 03.01.2021 
   * @param object user Object.
   * @param string msg zu Versendende Nachrricht.
   */
  function send($user, $msg) {
    LogHandler::writeLog("Send to Usr");
    LogHandler::writeLog("msg: ".$msg);

    if ($user->handshake) {
      $frame = $msg;
      $frame = $this->frame($msg);
      socket_write($user->socket, $frame, strlen($frame));
    } else { //! storage system überarbeiten
      $this->storage[] += ["user" => $user, "msg" => $msg];
    }
  }

  /**
   * Sendet etwas an alle User,
   * welche mit den Master-Socket in verbindung stehen.
   * @author Tim
   * @version 03.01.2021 
   * @since 03.01.2021 
   * @param string msg Nachrricht.
   */
  function broadcast($msg) {
    foreach ($this->users as $user) {
      if ($user->socket) {
        $this->send($user, $msg);
      }
    }
  }
  
  /**
   * Sendet Ping an User.
   * @author Tim
   * @version 21.01.2021
   * @since 21.01.2021 
   * @param object user Object.
   * @param object user user welcher angepingt werden soll.
   */
  function ping($user) {
    LogHandler::writeLog("ping gesendet");
    
    if ($user->handshake) {
      $frame = $this->frame("PING", "ping");
      socket_write($user->socket, $frame, strlen($frame));
    } else { 
      LogHandler::writeLog("user nicht mehr verbunden für Ping");
    }
  }

  /**
   * Zeigt alle Users des Sockets an.
   * @author Tim
   * @version 23.01.2021
   * @since 23.01.2021
   */
  function userList() {
    LogHandler::writeLog("---------USERLIST-------");
    foreach ($this->users as $user) {
      LogHandler::writeLog("User: ". $user->id);
    } 
    
    LogHandler::writeLog("-------USERLIST-------");
    foreach ($this->sockets as $socket) {
      LogHandler::writeLog("Sockets: ". $socket);
    } 
  }
}