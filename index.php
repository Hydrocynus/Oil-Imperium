<!DOCTYPE html>
<html lang="de">

  <head>
    <!--Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oil Imperium</title>
  </head>

  <body>
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
            <div class="d-flex flex-row justify-content-center">
              <div class="btn btn-outline-light">Spiel erstellen</div>
              <div class="btn btn-outline-light">Spiel beitreten</div>
            </div>
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
    src: url('CenturyGothic.ttf');
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
  .btn {
    margin: 0 2px;
  }
</style>

</html>