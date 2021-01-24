<?php
set_include_path("../php/");
require_once("default-html-head.php");
spl_autoload_register(function ($class) { require_once("classes/$class.php"); });
?>
<link rel="stylesheet" href="lobby.css">
<link rel="stylesheet" href="../home/home.css">
<script src="../js/classes/Socket.js"></script>
<script src="../js/classes/Xhr.js"></script>
<script src="../js/classes/Utils.js"></script>
<script src="../js/classes/GameControl.js"></script>
<script src="../js/classes/InstructionHandler.js"></script>
<script src="game.js" defer></script>
</head>

<body>
  <div id="background" class="d-flex">
    <div id="loading">
      <img src="../img/derrick.gif">
      <br>
      <span></span>
    </div>
    <!-- Mask-->
    <div id="lobby" class="mask d-flex align-items-center justify-content-center none">
      <!-- Container -->
      <div class="container text-white text-center">
        <h1 class="text-white">Lobby</h1>
        <h6 class="text-uppercase text-white">—&nbsp&nbsp Spielcode: <span id="code">----</span> &nbsp&nbsp—</h6>
        <!-- form -->
        <form class="row-12" action="" method="get">

          <!-- conatiner -->
          <div class="container">
            <div class="row">&nbsp;</div>
            <!-- Eigende Spieler anpassungen -->
            <div class="row">
              <div class="col">Name:<input type="text" value="Player" onchange="nameChange(this)"></div>
              <div class="col">Farbe:
                <label for="farbe"></label>
                <input id="farbe" type="color" value="Player" visible="false" onchange="colorChange(this)" style="display:none">
              </div>
              <div class="col"><input id="confirm" type="checkbox" onchange="readyChange(this)"><label for="confirm">Bereit:</label></div>

            </div>
            <!-- Eigende Spieler anpassungen -->
            <div class="row">&nbsp;</div>
            <!-- blur -->
            <div class="blur">
              <!-- Alle verfügbaren Spieler -->
              <div class="row">
                <div class="col"> </div>
              </div>
              <div class="row" style="border-bottom: rgba(95, 95, 95, 0.15) 5px solid;">
                <div class="col">
                  Spieler in der Lobby:
                </div>
              </div>
              <!-- Alle verfügbaren Spieler -->

              <!-- Tabellen Inhalt -->
              <div id="playerlist">
                <div class="row">
                  <div class="col">hier spieler db schrift farbe</div>
                  <div class="col">status</div>
                </div>

                <div class="row">
                  <div class="col">hier spieler db schrift farbe</div>
                  <div class="col">status</div>
                </div>
                <div class="row">
                  <div class="col">hier spieler db schrift farbe</div>
                  <div class="col">status</div>
                </div>
              </div>

              <!-- Tabellen Inhalt -->
            </div>
            <!-- blur -->
          </div>
          <!-- conatiner -->
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