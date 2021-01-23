<?php
set_include_path("./");
spl_autoload_register(function ($class) { require_once("classes/$class.php"); });
$buffer_closed = false;

if (!isset($_POST['fnc'])) exit();
startBuffer();
$_POST['fnc']();
if (!$buffer_closed) closeBuffer();

/**
 * Startet einen neuen Websocket Server.
 * @author Tobias
 * @version 03.01.2021
 * @since 31.12.2020
 * @return void
 */
function startWebSocket() {
  closeBuffer();
  $host = $_POST['hst'];
  $port = $_POST['prt'];
  exec("php StartSocket.php ". $host ." ". $port);
}

// --- Outputbuffer ---

/**
 * Startet den Outputbuffer.
 * @author Tobias
 * @copyright tomlgold2003 - https://www.php.net/manual/en/features.connection-handling.php -> Comments
 * @version 03.01.2020
 * @since 03.01.2020
 * @return void
 */
function startBuffer() {
  @ob_end_clean();
  header("Connection: close\r\n");
  header("Content-Encoding: none\r\n");
  ob_start();
}

/**
 * Beendet den Outputbuffer.
 * @author Tobias
 * @copyright tomlgold2003 - https://www.php.net/manual/en/features.connection-handling.php -> Comments
 * @version 03.01.2020
 * @since 03.01.2020
 * @return void
 */
function closeBuffer() {
  $buffer_closed = true;
  $size = ob_get_length();
  @header("Content-Length: $size");
  @ob_end_flush();     // Strange behaviour, will not work
  @flush();            // Unless both are called !
  @ob_end_clean();
}
