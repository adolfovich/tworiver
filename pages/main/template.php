<?php

if (isset($form["auth"])) {
  if ($form["auth"]) {
    $login = $form["login"];
    $pass = $form["pass"];

    if (isset($login) && strlen($login) != 0) {
  		if (isset($pass) && strlen($pass) != 0) {

  			$auth_login = $login;

  			$result_auth = $db->getRow("SELECT * FROM users WHERE email = ?s", $auth_login);

  			if ($result_auth) {

  				$user_email = $result_auth['email'];
  				$user_pass = $result_auth['pass'];
  				$user_name = $result_auth['name'];
  				$user_phone = $result_auth['phone'];
  				$user_is_admin = $result_auth['is_admin'];
  				$user_is_del = $result_auth['is_del'];

          //var_dump($user_pass);
          //var_dump(md5($pass));

  				if ($user_is_del == 1) {
            $message = ['type' => 'error', 'msg' => 'Данный пользователь заблокирован'];
  					//$is_auth = 0;
  				}	else if ($user_pass != md5($pass)) {
  					$message = ['type' => 'error', 'msg' => 'Не верный пароль'];
  				} else {
  					$is_auth = 1;
  					//генерируем сессию
  					$sid = md5(uniqid(rand(5,10), true));
  					//добавляем сессию в базу
            $db->query("UPDATE users SET session = ?s WHERE email = ?s", $sid, $user_email);
  					//mysql_query("UPDATE users SET session = '$sid' WHERE email = '$user_email'") or die(mysql_error());
  					// добавить в куки сессию
  					setcookie("session", $sid, time()+3600);  /* срок действия 1 час */
  					// добавляем в куки email
  					setcookie("user", $user_email, time()+2592000);  /* срок действия 30 дней */
  					// добавляем в куки имя
  					setcookie("user_name", $user_name, time()+2592000);  /* срок действия 30 дней */
  					// добавляем в куки если адми
  					//setcookie("user_is_admin", $user_is_admin, time()+2592000);  /* срок действия 30 дней */

            $_SESSION['login'] = $user_email;
            $_SESSION['id'] = $result_auth['id'];

            //$location = "Location: ".$_SERVER["REQUEST_URI"];
  					$location = "Location: /cab";
  					header($location);
  				}
  			}	else {
          $message = ['type' => 'error', 'msg' => 'Пользователь не найден'];
  			}
  		}	else {
        $message = ['type' => 'error', 'msg' => 'Не введен пароль'];
  		}
  	}	else {
  	  $message = ['type' => 'error', 'msg' => 'Не введен email'];
  	}
  } else {
    session_destroy();
  }
}


$menu = [
  ['name'=>'Главная', 'link' => '/'],
  ['name'=>'Новости', 'link' => 'news'],
  ['name'=>'Контакты', 'link' => 'contacts']
];

include ('tpl/main/header.tpl');

if ($url[0] === NULL || $url[0] === '') {
  include ('pages/main/default.php');
} else if (file_exists('pages/main/'.$url[0].'.php')) {
  include ('pages/main/'.$url[0].'.php');
} else {
  include ('pages/main/404.php');
}


include ('tpl/main/footer.tpl');
