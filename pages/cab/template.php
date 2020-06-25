<?php

if (!isset($_COOKIE['user'])) {
  $core->redir('/');
}

$menu = [
  ['name'=>'Главная', 'link' => '/'],
  ['name'=>'Новости', 'link' => 'news'],
  ['name'=>'Контакты', 'link' => 'contacts']
];

$cab_menu = [
  ['name'=>'Главная', 'link' => '/'],
  ['name'=>'Новости', 'link' => 'news'],
  ['name'=>'Контакты', 'link' => 'contacts']
];

//var_dump($_SESSION);

$user_data = $db->getRow("SELECT * FROM `users` WHERE `id` = ?i", $_SESSION['id']);
//var_dump($user_data);

$ind_month_start = $db->getRow("SELECT * FROM `Indications` WHERE `user` = ?i ORDER BY `id` ASC LIMIT 1", $user_data['id']);

if (!isset($url[1]) || $url[1] == '') {
  $content = 'pages/cab/default.php';
} else if (isset($url[1])) {

  if (file_exists('pages/cab/'.$url[1].'.php')) {
    $content = 'pages/cab/'.$url[1].'.php';
  } else {
    $content = 'pages/cab/404.php';
  }
}


include ('tpl/cab/template.tpl');
