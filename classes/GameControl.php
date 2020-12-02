<?php
set_include_path("../");
require_once('Barrel.php');
class GameControl extends Barrel {
  public function create_game() {
    $code = $this->generate_code();

  }

  // private function generate_code() {
  //   do {
  //     $code = generate_random_string(4);
  //   } while ()
  // }

  private function generate_random_string(int $length) {
    $chars = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    $length = count($chars);

    $string = '';
    for ($length; $length>0; $length--) {
      $string .= $chars[random_int(0, $length)];
    }

    return $string;
  }

  private function code_exists(string $code) {

  }
}