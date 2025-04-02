<!DOCTYPE html>
<html lang="ru">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?=$core->cfgRead('siteName')?></title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template --> <link href="css/blog-post.css" rel="stylesheet"> 
<!--script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script-->

  <script src="vendor/jquery/jquery.min.js"></script>

  <style>
  html, body {
    height: 100%;
  }
  .content {
    min-height: calc(100% - 120px);
    padding: 30px;
  }
  .small-input {
    width: 100px !important;
  }
  </style>

</head>

<body>

  <?php
  if (isset($message)) {
  ?>
  <script type="text/javascript">Swal.fire({type: "<?=$message['type']?>",text: "<?=$message['msg']?>"})</script>
  <?php
  }
  ?>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#"><img src="img/logo-mini.png" style="width: 30px; margin-right: 10px;"><?=$core->cfgRead('siteName')?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <?php
          foreach ($menu as $item) {
            if (($url[0] == '' && $item['link'] == '/') || $url[0] == $item['link']) {
              $active = 'active';
              $sr_only = '<span class="sr-only">(current)</span>';
            } else {
              $active = '';
              $sr_only = '';
            }
          ?>
          <li class="nav-item <?=$active?>">
            <a class="nav-link" href="<?=$item['link']?>"><?=$item['name']?><?=$sr_only?></a>
          </li>
          <?php
          }

          if ($core->login()) {
          ?>

          <li>
            <form method="POST">
            <span class="nav-link"><button name="auth" type="submit" class="btn btn-outline-secondary btn-sm" value="0">Выход</button></span>
          </li>
          <?php
          } else {
          ?>

          <li>
            <div class="dropdown" style="padding: 5px;">
              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Вход
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <form class="px-2 py-2" method="POST">
                  <div class="input-group input-group-sm py-2">
                    <input name="login" type="text" class="form-control" placeholder="Логин" aria-label="Логин" aria-describedby="basic-addon1" value="<?php if (isset($login)) echo $login?>">
                  </div>

                  <div class="input-group input-group-sm py-2">
                    <input name="pass" type="password" class="form-control" placeholder="Пароль" aria-label="Пароль" aria-describedby="basic-addon1">
                  </div>

                  <div class="input-group input-group-sm py-2">
                    <button name="auth" type="submit" class="btn btn-outline-dark btn-sm btn-block" value="1">Войти</button>
                  </div>
                </form>
              </div>
            </div>
          </li>

        <?php } ?>
        </ul>

      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container content">
