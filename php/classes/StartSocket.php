<?php

require_once("OilSocket.php");
require_once("LogHandler.php");// Tmp?

LogHandler::writeLog("Server start");
LogHandler::writeLog("host:". $argv[1]);
LogHandler::writeLog("port:". $argv[2]);

$host = $argv[1];
$port = $argv[2];

$oilSocket = new OilSocket($host, $port);
try {
  $oilSocket->start();
} catch (Exception $e) {
  print_r($e);
}

