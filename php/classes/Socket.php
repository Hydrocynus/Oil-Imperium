<?php 
abstract class Socket {
  protected $users = [];
  protected $sockets = []; // Über Client aufrufen ?
  protected $master;
  protected $storage = []; // nicht eingebunden 
  protected $serverid;
  protected $state;
  protected $bufSize;

  function __construct($addr, $port) {
    $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);
    socket_bind($this->master, $addr, $port);
    socket_listen($this->master);
    $this->sockets["master"] = $this->master;
    $this->serverid = uniqid();
    $this->state = "lobby";
    $this->bufSize = "2048";
  }

  abstract protected function onConnection($user);
  abstract protected function onMessage($user, $msg);
  abstract protected function onClose($user);

  /** 
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
   * @return void
   */
  function start() {
    LogHandler::writeLog("Start Server(". $this->serverid.")", true);

    while(true) {
      LogHandler::writeLog("tack(". $this->serverid.")[". $this->state ."]", false, "./logs/ticks/".$this->serverid.".txt");
      
      $this->heartbeat(60);

      //Handle Sockets @todo als fkt
      $write = $except = null;
      $read = $this->sockets;
      socket_select($read, $write, $except, 1);
            
      foreach($read as $socket) {
        //Master Socket-> connection handling
        if ($socket == $this->master) {
          $newReq = @socket_accept($socket);
          if ($newReq >= 0) { $this->connect($newReq); }
        } 
        else {//Client Handling
          $recv = @socket_recv($socket, $buf, $this->bufSize, 0);

          if ($recv === false) { //socket error
            
            $sockErrNo = socket_last_error($socket);
            LogHandler::writeLog("ERROR: Socket-err: ". $sockErrNo . "|". socket_strerror($sockErrNo));
            $this->disconnect($socket); 
          } 
          else if ($recv == 0) { //connection lost handling
            LogHandler::writeLog("ERROR: connection lost");
            $this->disconnect($socket); 
          }
          else { //client socket handling

            $u = $this->getUserBySocket($socket);
            if (!$u->handshake) { //Respond to Handshake
              $tmp = str_replace("\r", "", $buf);
              if (strpos($tmp, "\n\n") === false) { continue; } 
              $this->handshake($u, $buf);
            } else {  //recieve data
              $data = $this->deframe($u, $buf);
              $this->onMessage($u, $data["payload"]);
            }
          }
        }
      }

      $this->stopServer();
    }

  }

//--Server funktionen

  /** 
   * Durchläuft alle User.
   * Prüft anhand der Zeit des letzten Pings des Users, ob ein neuer Ping notwendig ist.
   * @author Tim
   * @version 09.01.2021
   * @since 09.12.2020
   * @param int pingTime die Differenz zwischen zwei Pings.
   * @return void
   */
  function heartbeat($pingTime) {
    foreach($this->sockets as $socket) {

      if ($socket == $this->master) { continue; }

      $u = $this->getUserBySocket($socket);
      if (time() -  $u->lastPing >= $pingTime ) {
        $u->lastPing = time();
        $this->ping($u); 
      }
    } 
  }

  /** 
   * Prüft ob User mit dem Server connected sind.
   * Falls keine User vorhanden sind wird dieser geschlossen.
   * @author Tim
   * @version 24.01.2021
   * @since 09.12.2020
   * @return void
   */
  function stopServer() {
    $u = $this->getUsers($this->users);

    if (count($u["conUsers"]) <= 0) {
      LogHandler::writeLog("STOP SERVER!",true);
      exit();
    }
  }

//--user-handling

  /** 
   * Wird aufgerufen wenn ein Client ein handshake request sendet.
   *  Prüft ob ein nicht verbundener User existiert.
   * Legt neuen User im users-Array an.
   * Legt neuen socket im socket-Array an.
   * @author Tim
   * @version 23.01.2021 
   * @since 03.12.2020
   * @param object Socket Object das sich mit dem master-Socket Verbindet. 
   * @return void
   */
  function connect($socket) {
    $unConUsers = $this->getUsers($this->users)["unConUsers"];
    
    if (count($unConUsers) > 0 ) {
      $newUser = $unConUsers[0];
      $newUser->setUser($socket);
      LogHandler::writeLog("Reconnect: ". $newUser->id);
    } else {
      $newUser = new SocketClient(uniqid(), $socket); 
    }

    $this->users[$newUser->id] = $newUser;
    $this->sockets[$newUser->id] = $socket;
    
    LogHandler::writeLog("Connect: ". $newUser->id);
    $this->userList();
  }

  /**
   * Entfernt Socket aus Socket Array.
   * Schliesst Socket. 
   * @author Tim
   * @version 23.01.2021 
   * @since 03.12.2020
   * @param object Socket Object, welches entfernt werden soll
   * @return void
   */
  function disconnect(&$socket) {
    $oldUser = $this->getUserBySocket($socket);
    unset($socket);
    
    if ($oldUser === null) return;

    unset($this->sockets[$oldUser->id]);
    $this->users[$oldUser->id]->unsetUser();

    $this->onClose($oldUser); //! notw?
    socket_close($oldUser->socket);

    LogHandler::writeLog("Disconnect user: ". $oldUser->id);
    $this->userList();

    if ($this->state == "lobby") {$this->deleteUser($oldUser->id); }
  }

  /** 
   * Entfernt User aus users Array an.
   * @author Tim
   * @version 23.01.2021
   * @since 03.12.2020
   * @param string id des Users, welcher user gelöscht werden soll. 
   * @return void
   */
  function deleteUser($id) {
    if ($this->users[$id] === null) return;

    unset($this->users[$id]);

    LogHandler::writeLog("DELETE user: ". $id);
    $this->userList();
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
   * @return void
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

      } else if (stripos($line,"get ") !== false) {  //! idk
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
    
    // moegliche header angaben:
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

        "length"  => (ord($frame[1]) >= 128) ? ord($frame[1]) - 128 : ord($frame[1]),//bindec(substr($bin, 9,7)),//! frame groeßer machen
        "mask"    => "",//substr($frame, 2,4),//substr($bin, 16,32),
    ];

    //length holen und maske festlegen
    if ($header['length'] == 126) {
      if ($header['hasmask']) {
        $header['mask'] = $frame[4] . $frame[5] . $frame[6] . $frame[7];
        $header['start'] = 8;
      }
      $header['length'] = ord($frame[2]) * 256 + ord($frame[3]);
    } elseif ($header['length'] == 127) {
      
      $header['start'] = 10;
      if ($header['hasmask']) {
        $header['mask'] = $frame[10] . $frame[11] . $frame[12] . $frame[13];
        $header['start'] = 14;
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
      $header['start'] = 6;
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
  function deframe(&$user, $frame) {
    $header = $this->getHeaderOfFrame($frame);
    $payload = substr($frame, $header["start"], $header["length"]); //length > 126 !
    $payload = Utils::xorStr($payload, $header["mask"]);

    $data = []; 
    $data["payload"] = $payload;
    $data["header"] = $header;

    LogHandler::writeLog("DEFRAME: ", true);
    LogHandler::writeLog("Frame: ". Utils::getBinOfFrame($frame));
    LogHandler::writeLog("payload: ". $data["payload"]);

    // //check rsv in Header //todo 
    // if (ord($header['rsv1']) + ord($header['rsv2']) + ord($header['rsv3']) > 0) {
    //   //$this->disconnect($user); // todo: fail connection
    //   return false;
    // }

    //opcode Verarbeiten
    LogHandler::writeLog("opcode: ". $header["opcode"]);
    switch($header["opcode"]) {
      case 0:
      case 1: //msg
        LogHandler::writeLog($data);
      case 2: //binary-data
        break;

      case 8: //closing frame
        LogHandler::writeLog("->got closing Frame");
        $this->disconnect($user);
        return "";

      case 9: //ping frame info:kein client-ping eingebaut, aber server-ping system 
        LogHandler::writeLog("->got ping Frame");
        $reply = $this->frame($payload, $user, 'pong'); 
        $this->send($user, $reply);
        return false;

      case 10://pong frame
        LogHandler::writeLog("->got pong Frame (do nothing)");
        return false;
        break;

      default:
        return false;
        break;
    }

    return $data; //!todo
    //mutiple Frames Handling | Frame > 2048 bits //todo
    
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
   * @return void
   */
  function send($user, $msg) {
    LogHandler::writeLog("SEND to: ". $user->id);
    LogHandler::writeLog("Sended Msg: ".$msg);

    if ($user->handshake) {
      $frame = $msg;
      $frame = $this->frame($msg);
      socket_write($user->socket, $frame, strlen($frame));
    } else { //! storage system nutzen
      $this->storage[] += ["user" => $user->id, "msg" => $msg];
    }
  }

  /**
   * Sendet etwas an alle User,
   * welche mit den Master-Socket in verbindung stehen.
   * @author Tim
   * @version 03.01.2021 
   * @since 03.01.2021 
   * @param string msg Nachrricht.
   * @return void
   */
  function broadcast($msg) {
    foreach ($this->users as $user) {
      if ($user->socket) {
        LogHandler::writeLog("Frame: ". Utils::getBinOfFrame($msg));
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
   * @return void
   */
  function ping($user) {
    if ($user->handshake) {
      $frame = $this->frame("PONG", "ping");
      socket_write($user->socket, $frame, strlen($frame));
      LogHandler::writeLog("PING to: ". $user->id, true);
      
    } else { 
      LogHandler::writeLog("PING Error: user not connected");
    }
  }

//--Hilfsfunktionen
  /**
   * Zeigt alle Users des Sockets im log an.
   * @author Tim
   * @version 23.01.2021
   * @since 23.01.2021
   * @return void
   */
  function userList() {
    $u = count($this->users);
    $s = count($this->sockets);

    LogHandler::writeLog("-----------------------", true, "./logs/list.txt");

    LogHandler::writeLog("USERLIST (".$u.")", false, "./logs/list.txt");
    foreach ($this->users as $user) {
      $noSock = "";
      if (!isset($user->socket)) {
        $noSock = " (not connected)";
      }
      LogHandler::writeLog("User: ". $user->id." ".$user->name ." ".$user->color . $noSock, false, "./logs/list.txt");
    } 
    
    LogHandler::writeLog("SocketLIST (".$s.")", false, "./logs/list.txt");
    foreach ($this->sockets as $k => $socket) {
      LogHandler::writeLog("Sockets: ". $k, false, "./logs/list.txt");
    } 
    
    LogHandler::writeLog("-----------------------", false, "./logs/list.txt");
  }
  
  /** 
   * Sortiert alle Users des Servers, ob sie ein socket besitzen oder nicht. 
   * @author Tim
   * @version 24.01.2021
   * @since 09.12.2020
   * @return array ["conUsers"] enthält alle Users mit socket; ["unConUsers"] enthält alle Users ohne socket
   */
  function getUsers($users) {
    $info = []; 
    $info["conUsers"] = [];
    $info["unConUsers"] = [];

    foreach ($users as $u) {
      if (isset($u->socket)) {
        array_push($info["conUsers"], $u);
      } else {
        array_push($info["unConUsers"], $u);
      }
    }
    
    return $info;
  }

  function sendPlayerlist($user) {    
    foreach($this->users as $u) {
      $this->send($user, json_encode(["userAdd", json_encode($u->getUserInfo())])); 
    }
  }
}