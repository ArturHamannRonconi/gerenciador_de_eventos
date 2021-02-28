<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador De Eventos</title>
    <link type="text/css" rel="stylesheet" href='<?= "{$assets}/css/style.css" ?>'>
    <link rel="stylesheet" href='<?= "{$base}/assets/css/{$viewName}.css"?>''>
  </head>
  <body>
    <header id="header">
    <div class="container">
        <div class="home">
          <div class="pergunta">
            <h1><?= $pergunta ?></h1>
          </div>
        </div>
      </div>
    </header>

    <?php $this->renderView($viewName, $modelData) ?>

    <footer id="footer">
    <div class="container">
        <div class="menu">
          <a href="JavaScript: window.history.back();">
            <img src='<?= "{$assets}/medias/previous.svg" ?>' id="previous" alt="voltar" tabindex="2">
          </a>
          <a href='<?= "{$base}/home" ?>'>
          <img src='<?= "{$assets}/medias/home.svg" ?>' id="home" alt="home" tabindex="1">
        </a>
        <a href="JavaScript: window.history.forward();">
          <img src='<?= "{$assets}/medias/previous.svg" ?>' id="forward" alt="avançar" tabindex="3">
        </a>
        </div>
      </div>
    </footer>
  </body>
</html>