<?php

$is_auth = 0;

if (isset($_POST['logout'])) {
	setcookie("session", $sid, time()-3600);  /* удаляем куки */
	setcookie("user", $user_email, time()-3600);
	setcookie("user_name", $user_name, time()-3600);
	setcookie("user_is_admin", $user_is_admin, time()-3600);
	$is_auth = 0;
	
	$location = "Location: ".$_SERVER["REQUEST_URI"];
	header($location);
}




if (isset($_COOKIE["session"]) && isset($_COOKIE["user"])) {
	//проверяем сессию пользователя
	$result_session = mysql_query("SELECT session FROM users WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());
	if (mysql_result($result_session, 0) == $_COOKIE["session"]) {
		$is_auth = 1;
		
	}
	
}


if (isset($_POST['auth']) && $_POST['auth'] == '1') {

	
	if (isset($_POST['auth_login']) && strlen($_POST['auth_login']) != 0) {
		if 	(isset($_POST['auth_pass']) && strlen($_POST['auth_pass']) != 0) {
			
			$auth_login = $_POST['auth_login'];
			
			$q_auth = "SELECT * FROM users WHERE email = '$auth_login'";
			echo $q_auth;
			
			$result_auth = mysql_query($q_auth) or die(mysql_error());
			
			if (mysql_num_rows($result_auth) > 0) {
			
				while ($row = mysql_fetch_assoc($result_auth)) {
					$user_email = $row['email'];
					$user_pass = $row['pass'];
					$user_name = $row['name'];
					$user_phone = $row['phone'];
					$user_is_admin = $row['is_admin'];
					$user_is_del = $row['is_del'];
				}
				
				
				
				if ($user_is_del == 1) {
					$error_msg = '<script type="text/javascript">swal("Внимание!", "Данный пользователь заблокирован", "info")</script>';
					//$is_auth = 0;
				}
				else if ($user_pass != md5($_POST['auth_pass'])) {
					 
					$error_msg = '<script type="text/javascript">swal("Внимание!", "не верный пароль", "info")</script>';
				}
				else {
					$is_auth = 1;
					//генерируем сессию
					$sid = md5(uniqid(rand(5,10), true));
					//добавляем сессию в базу
					mysql_query("UPDATE users SET session = '$sid' WHERE email = '$user_email'") or die(mysql_error());
					// добавить в куки сессию
					setcookie("session", $sid, time()+3600);  /* срок действия 1 час */
					// добавляем в куки email
					setcookie("user", $user_email, time()+3600);  /* срок действия 1 час */
					// добавляем в куки имя
					setcookie("user_name", $user_name, time()+3600);  /* срок действия 1 час */
					// добавляем в куки если адми
					setcookie("user_is_admin", $user_is_admin, time()+3600);  /* срок действия 1 час */
					
					$location = "Location: ".$_SERVER["REQUEST_URI"];
					header($location);
				}
			
			}
			else {
				$error_msg = '<script type="text/javascript">swal("Внимание!", "Пользователь не найден", "info")</script>';
			}
		}
		else {
			$error_msg = '<script type="text/javascript">swal("Внимание!", "Не введен пароль", "info")</script>';
		}
	}

	else {
		$error_msg = '<script type="text/javascript">swal("Внимание!", "Не введен email", "info")</script>';
		//echo 'не введен логин';
	}

}
?>