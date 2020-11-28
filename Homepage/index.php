<!DOCTYPE html>
<html lang="de">

  <head>
    <!--Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oil Imperium</title>
    <script>
      function checkInput(input) {
        console.debug("test");
        input.value = input.value.toUpperCase();
        input.value = input.value.replace(/\s/, "");
        input.value = input.value.substring(0, 4);
      }
    </script>
  </head>

  <body>
    <?php
      /**
       * Generiert einen HTML-Button fuer eine form.
       * @author Tobias Tim
       * @version 28.11.2020 Bugfix (Tim)
       * @since 28.11.2020
       * @param string $action Schluesselattribut der Anfrage.
       * @param string $value Wert der Anfrage.
       * @param string $text Inhalt des Buttons.
       * @param string $type (Optional) Typ des Form-Buttons. Standard ist submit.
       * @param string $classes (Optional) Wenn angegeben, wird das class-Attribut ueberschrieben.
       * @param string $attr (Optional) Zusaetzliche Attribute koennen hier angegeben werden.
       * @return string Der generierte Button. duh!
       */
      function create_Button(string $action, string $value, string $text, string $type = "submit", string $classes = null, string $attr = "") {
        if (!in_array($type, ['submit', 'reset', 'button'])) $type = "submit";

        if (!isset($classes)) $classes = "col-auto mx-1 btn-outline-light";

        return "<button type=\"$type\" class=\"btn $classes\" name=\"$action\" value=\"$value\" $attr>$text</button>";
      }

      /**
       * Generiert ein HTML-Input fuer eine form.
       * @author Tobias
       * @version 28.11.2020
       * @since 28.11.2020
       * @param string $action Schluesselattribut der Anfrage.
       * @param string $value Wert der Anfrage.
       * @param string $placeholder Platzhalter des Inputfeldes.
       * @param string $type (Optional) Typ des Inputfeldes. Standard ist text.
       * @param string $classes (Optional) Wenn angegeben, wird das class-Attribut ueberschrieben.
       * @param string $attr (Optional) Zusaetzliche Attribute koennen hier angegeben werden.
       * @return string Das generierte Input. duh!
       */
      function create_Input(string $action, string $value, string $placeholder, string $type = "text", string $classes = null, string $attr = "") {
        if (!in_array($type, ['text','number','button','checkbox','color','date','datetime-local','email','file','hidden','image','month','number','password','radio','range','reset','search','submit','tel','text','time','url','week'])) $type = "text";

        if (!isset($classes)) $classes = "col-auto mx-1 btn-outline-light";

        return "<input type=\"$type\" class=\"btn $classes\" name=\"$action\" value=\"$value\" placeholder=\"$placeholder\" $attr>";
      }

      $btnCreate = create_Button('action', 'create', 'Spiel erstellen');
      $btnShowInput = create_Button('action', 'showInput', 'Spiel beitreten');
      $inpJoin = create_Input('code', '', 'CODE', 'text', null, 'autofocus oninput="checkInput(this)" size="4" maxlength="4" autocomplete="off"');
      $btnJoin = create_Button('action', 'join', '▶');
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
              $action = isset($_GET['action']) ? $_GET['action'] : "";

              switch ($action) {
                case 'create':
                  echo "du hast das spiel erstellt";
                  break;
                case 'showInput':
                  echo $inpJoin;
                  echo $btnJoin;
                  break;
                case 'join':
                  echo "Du bist dem Spiel mit dem code {$_GET['code']} beigetreten";
                  break;
                default:
                  echo $btnCreate;
                  echo $btnShowInput;
              }
            ?>
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