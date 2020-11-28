<!DOCTYPE html>
<html lang="de">

  <head>
    <!--Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oil Imperium</title>
  </head>

  <body>
    <?php
      /**
       * Generiert einen HTML-Button fuer ein form.
       * @author Tobias Tim
       * @version 28.11.2020 Bugfix (Tim)
       * @since 28.11.2020
       * @param string $action Schluesselattribut der Anfrage bei Klick.
       * @param string $value Wert der Anfrage bei Klick.
       * @param string $text Inhalt des Buttons.
       * @param string $type (Optional) Typ des Form-Buttons. Standard ist submit.
       * @param string $classes (Optionak) Wenn angegeben, wird das class-Attribut ueberschrieben.
       * @return string Der generierte Button. duh.
       */
      function create_Button(string $action, string $value, string $text, string $type = "submit", string $classes = null) {
        if (!in_array($type, ['submit', 'reset'])) $type = "submit";
        if (!isset($classes)) $classes = "col-auto mx-1 btn-outline-light";

        return "<button type=\"$type\" class=\"btn $classes\" name=\"$action\" value=\"$value\">$text</button>";
      }

      $btnCreate = create_Button("action", "create", "Spiel Erstellen");
      
      $JoinBtn = '<button type="submit" class="col-auto btn mx-1 btn-outline-light" value="join" name="action">Spiel beitreten</button>';
      $CreateBtn = '<button type="submit" class="col-auto btn mx-1 btn-outline-light" value="create" name="action">Spiel erstellen</button>';
      $CodeInput = '<input type="text" name="gameId" class="col-auto mx-1">';
      $CodeBtn = '<button type="submit" class="col-auto btn mx-1 btn-outline-light" value="JoinWithCode" name="action">Spiel beitreten mit Code</button>';
    ?>
  <!-- Full Page Intro -->
  <div class="view" style="background-image: url('Oil.png'); background-repeat: no-repeat; background-size: cover; background-position: center center;">
    <!-- Mask & flexbox options-->
    <div class="mask d-flex align-items-center justify-content-center">
      <!-- Content -->
      <div class="container">
        <!--Grid row-->
        <div class="row">
          <!--Grid column-->
          <div class="col text-white text-center">
            <h1 class="text-white"><strong>Öl Imperium</strong></h1>
            <hr>
            <h6 class="text-uppercase text-white"><strong>—&nbsp&nbsp Das wirtschaftliche Strategiespiel &nbsp&nbsp—</strong></h6>
            <hr>
            <form class="row-12" action="index.php" method="get">
            <?php 
            if(!isset($_GET["action"])){
              // $btnShowCodeInput = create_Button("action", "showCodeInput", "Spiel ");
              echo $btnCreate . '<input type="text" name="gameId" class="col-auto mx-1" value="Gamecode Eingeben">';
            }
            elseif($_GET["action"]=="create"){
                echo "du hast das spiel erstellt";
            }
            elseif($_GET["action"]=="join"){
                echo "Bitte gib hier den Game Code ein";
            }
            ?>
            
            <button type="submit" class="col-auto btn mx-1 btn-outline-light" value="joinCode" name="action"></button>
            </form>
          </div>
          <!--Grid column-->
        </div>
        <!--Grid row-->
      </div>
      <!-- Content -->
    </div>
    <!-- Mask & flexbox options-->
  </div>
  <!-- Full Page Intro -->
  </body>

<style>
  @font-face {
    font-family: 'Century Gothic';
    src: url('../CenturyGothic.ttf');
  }

  html,body,.view{
    height: 100%;
  }
  *{
    font-family:"Century Gothic";
  }
  .mask{
    height: 100%;
    width: 100%;
    background: radial-gradient(circle, rgb(0, 0, 0, 0.8) 15%, transparent 62%);
  }
</style>

</html>