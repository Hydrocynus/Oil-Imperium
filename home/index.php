<!--
 - Startseite des Spiels.
 - Zeigt und behandelt Spielerstellung und Spielbeitritt.
 - @author Jannis Tim Tobias
 - @version 28.11.2020
 - @since 25.11.2020
-->
<?php
  require_once("../default-html-head.php");
  require_once("../classes/Utils.php");

  $btnCreate = create_Button('action', 'create', 'Spiel erstellen');
  $btnShowInput = create_Button('action', 'code', 'Spiel beitreten');
  $inpJoin = create_Input('code', '', 'CODE', 'text', null, 'autofocus oninput="checkCode(this)" maxlength="4" autocomplete="off"');
  $btnJoin = create_Button('action', 'join', '<i class="fas fa-check"></i>', "submit", null, 'disabled');
  $btnHome = create_Button('', '', '<i class="fas fa-home"></i>', "button", null, 'onclick="d3.select(\'form\').node().submit()"');
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
        <h6 class="text-uppercase text-white">—&nbsp&nbsp Das wirtschaftliche Strategiespiel &nbsp&nbsp—</h6>
        <hr>
        <!-- form -->
        <form class="row-12" action="" method="get">
        <?php
          $action = isset($_GET['action']) ? $_GET['action'] : "";

          switch ($action) {
            case 'create':
              echo "du hast das spiel erstellt";
              break;
            case 'code':
              echo $btnHome;
              echo create_Button('', '', '<i class="fas fa-long-arrow-alt-left"></i>', "button", null, 'onclick="d3.select(\'form\').node().submit()"');
              echo create_Button('', '', '<i class="fas fa-arrow-left"></i>', "button", null, 'onclick="d3.select(\'form\').node().submit()"');
              echo create_Button('', '', '<i class="fas fa-reply"></i>', "button", null, 'onclick="d3.select(\'form\').node().submit()"');
              echo $inpJoin;
              echo $btnJoin;
              echo "<br>Welcher Home-Button sieht besser aus?";
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
        <!-- form -->
      </div>
      <!-- Container -->
    </div>
    <!-- Mask & flexbox options-->
  </div>
  <!-- Full Page Intro -->
  </body>
</html>