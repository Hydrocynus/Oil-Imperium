<?php
set_include_path("./");
spl_autoload_register(function ($class) { require_once("classes/$class.php"); });

$host = $argv[1];
$port = $argv[2];

// $connection = @fsockopen($host, $port);
// if (is_resource($connection)) {
//     LogHandler::writeLog("Socket bereits offen (BRUUUUUUUUUUUUUUUUUHH)");
//     fclose($connection);
//     return;
// }

$oilSocket = new OilSocket($host, $port);

LogHandler::writeLog("start new Server on: ". $host ." ". $port); 

try {
  $oilSocket->start();
} catch (Exception $e) {
  print_r($e);
}

