<!--
 - Startseite des Spiels.
 - Zeigt und behandelt Spielerstellung und Spielbeitritt.
 - @author Jannis Tim Tobias
 - @version 03.01.2021
 - @since 25.11.2020
-->
<?php
  set_include_path("../php/");
  require_once("default-html-head.php");
  spl_autoload_register(function ($class) { require_once("classes/$class.php"); });

  $barrel = new GameControl();

  $btnCreate    = Utils::createButton('action', 'create', 'Spiel erstellen');
  $btnShowInput = Utils::createButton('action', 'code', 'Spiel beitreten');
  $inpJoin      = Utils::createInput('code', '', 'CODE', 'text', null, 'autofocus oninput="checkCode(this)" maxlength="4" autocomplete="off"');
  $btnJoin      = Utils::createButton('action', 'join', '<i class="fas fa-check"></i>', "submit", null, 'disabled');
  $btnHome      = Utils::createButton('', '', '<i class="fas fa-home"></i>', "button", null, 'onclick="d3.select(\'form\').node().submit()"');
?>
  <link rel="stylesheet" href="home.css">
  <script src="home.js"></script>
  </head>

  <body>
  <!-- Full Page Intro -->
  <div id="background">
    <!-- Mask-->
    <div class="mask d-flex align-items-center justify-content-center">
      <!-- Container -->
      <div class="container text-white text-center">
        <h1 class="text-white">&Oumll Imperium</h1>
        <hr>
        <h6 class="text-uppercase text-white">
          —&nbsp&nbsp
          <?php echo rand(0,10) === 0 ? "Das strategische Wirtschaftsspiel" : "Das wirtschaftliche Strategiespiel" ?>
          &nbsp&nbsp—
        </h6>
        <hr>
        <!-- form -->
        <form class="row-12" action="" method="get">
        <?php
          $action = isset($_GET['action']) ? $_GET['action'] : "";

          switch ($action) {
            case 'create':
              $code = $barrel->createGame();
              echo $btnHome;
              echo "Weiterleitung zu Lobby... (WIP)";
              $barrel->joinGame($code);
              break;
            case 'code':
              echo $btnHome;
              echo $inpJoin;
              echo $btnJoin;
              break;
            case 'join':
              $code = $_GET['code'];
              echo $btnHome;
              echo "Weiterleitung zu Lobby... (WIP)";
              $barrel->joinGame($code);
              break;
            default:
              echo $btnCreate;
              echo $btnShowInput;
          }
        ?>
        </form>
        <!-- form -->
      </div>
      <!-- Container -->
    </div>
    <!-- Mask & flexbox options-->
  </div>
  <!-- Full Page Intro -->
  </body>
</html>