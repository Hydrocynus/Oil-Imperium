<?php
set_include_path("../");
require_once("php/default-html-head.php");
require_once("php/Utils.php");
?>
<link rel="stylesheet" href="lobby.css">
<link rel="stylesheet" href="../home/home.css">
</head>

<body>
  <div id="background">
    <!-- Mask-->
    <div class="mask d-flex align-items-center justify-content-center">
      <!-- Container -->
      <div class="container text-white text-center">
        <h1 class="text-white">Lobby</h1>
        <h6 class="text-uppercase text-white">—&nbsp&nbsp Spielcode: <?php echo "Code" ?> &nbsp&nbsp—</h6>
        <!-- form -->
        <form class="row-12" action="" method="get">

          <!-- conatiner -->
          <div class="container">
            <!-- Eigende Spieler anpassungen -->
            <div class="row">
              <div class="col">Name:<input type="text" value="Player"></div>
              <div class="col">Farbe:<input type="color" value="Player"></div>
              <div class="col">Bereit:<input type="checkbox"></div>
            </div>
            <!-- Eigende Spieler anpassungen -->

            <!-- blur -->
            <div class="blur">
              <!-- Alle verfügbaren Spieler -->
              <div class="row">
                <div class="col"> </div>
              </div>
              <div class="row">
                <div class="col">
                  Spieler in der Lobby:
                </div>
              </div>
              <!-- Alle verfügbaren Spieler -->

              <!-- Tabellen Inhalt -->
              <div class="row">
                <div class="col">hier spieler db schrift farbe</div>
                <div class="col">status</div>
                <div class="w-100"></div>
                <div class="col">hier spieler db schrift farbe</div>
                <div class="col">status</div>
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