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
    <div class="mask">
      <!-- Content -->
      <div class="container mx-auto">
        <!--Grid row-->
        <div class="row align-middle">
          <!--Grid column-->
          <div class="col text-white text-center">
            <h1 class="text-white"><strong>Oil Imperium</strong></h1>
            <hr class="hr">
            <h5 class="text-uppercase text-white"><strong>Spielmenü</strong></h5>
            <a class="btn btn-outline-light">Create Game</a>
            <a class="btn btn-outline-light">Join Game</a>
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
  html,body,.view{
    height: 100%;
  }
  *{
    font-family:"Century Gothic";
  }
  .mask{
    height: 100%;
    width: 100%;
    background-color: rgba(0,0,0,0.5);
  }
</style>

</html>