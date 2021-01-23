<?php

set_include_path("./");
spl_autoload_register(function ($class) { require_once("classes/$class.php"); });

LogHandler::writeLog("StartSocket in ". getcwd() ); //!
LogHandler::writeLog("Server start");
LogHandler::writeLog("host:". $argv[1]);
LogHandler::writeLog("port:". $argv[2]. "\n");

$host = $argv[1];
$port = $argv[2];

$oilSocket = new OilSocket($host, $port);
try {
  $oilSocket->start();
} catch (Exception $e) {
  print_r($e);
}

