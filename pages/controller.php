<?php
//var_dump($url);
if (isset($url[1]) && $url[1] == 'logout') {
  $core->logout();
} else if ($url[0] == 'cab') {
  include ('pages/cab/template.php');
} else if (strpos($url[0], 'login') === 0) {
  include ('pages/cab/login.php');
} else if ($url[0] == 'register' || $url[0] == 'registration') {
  include ('pages/cab/register.php');
} else if ($url[0] == 'reminder') {
  include ('pages/cab/reminder.php');
} else if ($url[0] == 'captcha') {
  include ('pages/captcha.php');
} else if ($url[0] == 'online_payment') {
  include ('pages/main/online_payment.php');
} else {
  include ('pages/main/template.php');
}
