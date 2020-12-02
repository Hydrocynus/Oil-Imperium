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
        <h6 class="text-uppercase text-white">—&nbsp&nbsp Spielcode: Code &nbsp&nbsp—</h6>
        <!-- form -->
        <form class="row-12" action="" method="get">
          <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">  </th>
              <th scope="col">Name</th>
              <th scope="col">Color</th>
              <th scope="col">Bereit</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="text"></td>
              <td><input type="color"></td>
              <td><input type="checkbox"></td>
            </tr>
          </tbody>
        </table>
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