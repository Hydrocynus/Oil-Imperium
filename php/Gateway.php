<?php
set_include_path("./");
spl_autoload_register(function ($class) { require_once("classes/$class.php"); });

if (!isset($_POST['fnc'])) exit();
echo json_encode($_POST['fnc']());

/**
 * 
 * @author Tobias
 * @version 31.12.2020
 * @since 31.12.2020
 * @return void
 */
function startWebSocket() {
  return true;
}