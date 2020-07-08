<?php
/**
 * Project:     SNT-control
 * @copyright 2019 Alexandr Doroshenko
 * @version 0.0.1
 */

class Core
{
    /**
  * Содержит текущий url адрес в виде  массива
  * @var array $url
    */
  var $url;


  function __construct()
	{
      //$this->_conf();
      //$this->_ssl();
      $this->ip();
      //$this->host();
	    //$this->_currency();
      $this->_setURL();
      //$this->_qUrl();
      //$this->_userAuth();
      //$this->_security();
      //$this->_getMess();
      //$this->_getMess2();
      //$this->_lang();
      //$this->_agent();
      //$this->_referer();
      $this->form = $this->form();
      //$this->get = $this->_get();
    }

    public function setGet()
    {
      $full_url = $_SERVER['REQUEST_URI'];
      $return_arr = [];
      $url_arr = explode('?', $full_url);
      if (isset($url_arr[1])) {
        $get_params = explode('&', $url_arr[1]);
        foreach($get_params as $value) {
          $param = explode('=', $value);
          $return_arr[$param[0]] = $param[1];
        }
      }

      return $return_arr;
    }

    public function debuging($arg, $comment = 'debug')
    {
      global $debug_mode;
      if ($debug_mode) {
        echo $comment . ' = ';
        var_dump($arg);
        echo '<hr>';
      }
    }

    function _setURL()
  	{

       if(stristr($_SERVER['HTTP_HOST'], 'www.'))  {
         $this->redir('http://'.str_replace('www.', '', $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
       }

       /*if (!isset($_SERVER['REDIRECT_URL'])) {
         $url = $this->_filterUrl($_SERVER['REQUEST_URI']);
       } else {
         $url = $this->_filterUrl($_SERVER['REDIRECT_URL']);
       }*/
       $url = $this->_filterUrl($_SERVER['REQUEST_URI']);
  	   $url = substr($url, 1, strlen($url));

  	   $this->full_url = $url;
       //$this->debuging($this->full_url, '_setURL $full_url');
  	   $url = explode('/', $url);
       //$this->debuging($url, '_setURL $url');
       if($url){
  		   foreach($url as $url){
           $url = preg_replace("/\?.+/", "", $url);

           $this->url[] =   $this->filterAllowUrl($url);
         }
  	   }
  	}

    function filterAllowUrl($url)
  	{
      $allow_url = '';
      $allow = '?1234567890qwertyuiopasdfghjklzxcvbnm_-';
      for($i=0; $i<strlen($url); $i++){
        for($ii=0; $ii<strlen($allow); $ii++){
  	       if($url[$i] == $allow[$ii]) $allow_url .=  $url[$i];
        }
      }
      //return strtok($allow_url, '?');
      return $allow_url;
  	}

    function _filterUrl($url)
  	{
        $url = strtolower($url);
  	    $url = str_replace('"', '',  $url);
  	    $url = str_replace("'", '',  $url);
        $url = htmlspecialchars($url);
        //$url = mysqli_real_escape_string($url);
        //$this -> debuging($url, '_filterUrl');
  	    return $url;
  	}

    public function redir($url  = ''){
	   if(!$url)  $url = $_SERVER['REQUEST_URI'];
	   header("HTTP/1.1 301 Moved Permanently");
     header("location: $url");
     exit;
    }

    public function jsredir($url = '../'){
     echo "<script>document.location.href='".$url."'</script>";
       exit;
    }

    public function form($form = ''){

      function array_map_recursive($callback, $value){
         if (is_array($value)) {
           return array_map(function($value) use ($callback) { return array_map_recursive($callback, $value); }, $value);
         }
         return $callback($value);
      }
		  if(!$form) $form = $_POST;
      //$this -> debuging($_GET, 'form $_GET');
      if($form){
			//$form = array_map_recursive('mysql_real_escape_string', $form);
			//if($this->user['type'] != 'admin' && $this->user['type'] != 'moder') $form = array_map_recursive('htmlspecialchars', $form);
			$form = array_map_recursive('trim', $form);
            return $form;
        }
    }

    public function ip()
  	{
  	  $ip = $_SERVER['REMOTE_ADDR'];
  	  $this->ip  = $ip;
  	  return $ip;
  	}

    public function login()
    {
      if (isset($_SESSION['login'])) {
        //echo 's1';
        if (isset($_COOKIE['PHPSESSID'])) {
          //echo 's2';
          if (isset($_COOKIE['user']) && $_COOKIE['PHPSESSID'] == session_id() && $_SESSION['login'] == $_COOKIE['user']) {
            $session_time = $this->cfgRead('session_time');
            //echo 's3';
            if (setcookie ('user', null, -1, '/cab')) {
              $email = $_COOKIE['user'];
              setcookie ('user', null, -1, '/cab');
              setcookie ("user", $email, time() + $session_time, '/cab');
              setcookie ('user', null, -1, '/');
              setcookie ("user", $email, time() + $session_time, '/');

            }
            return true;
          }
        } else {
          session_destroy();
        }
      }
      return false;
    }

    function sendMyMail($subject, $text , $address = '', $errors = 0)
    {
      require_once('PHPM/src/Exception.php');
      require_once('PHPM/src/PHPMailer.php');
      require_once('PHPM/src/SMTP.php');
      require_once('PHPM/config.php');
      //print "ERROR";
      //$admin_mail = 'avtomain.fond@gmail.com';
      $admin_mail = 'adolfovich.alexashka@gmail.com';

    	if ($address == '') {
    		$address = $admin_mail;
    	}

    	try {
    		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
    		$mail->isSMTP();
    		$mail->CharSet = "utf-8";
    		$mail->SMTPDebug = 0;
    		$mail->Debugoutput = 'html';
    		$mail->Host = 'smtp.gmail.com';
    		$mail->Port = '587';
    		$mail->SMTPSecure = 'tls';
    		$mail->SMTPAuth = true;
    		$mail->Username = 'robot@avtomain.com';
    		$mail->Password = 'dndfeaCR26Heq8PVeg';
    		$mail->setFrom($smtp_username, 'Автомайн');
    		$mail->addAddress($address);
    		$mail->Subject = $subject;
    		$mail->Body    = $text;
    		$mail->AltBody = $text;
    		$mail->IsHTML(true);
    		$mail->send();
    		$error_send = 0;
    	} catch (Exception $e) {
    		$error_send = $mail->ErrorInfo;
    		$this->writeLog('email', 'Ошибка отправки письма 1'.$mail->ErrorInfo.' на адрес '.$address);

    		try {
    			$mail = new PHPMailer\PHPMailer\PHPMailer(true);
    			$mail->isSMTP();
    			$mail->CharSet = "utf-8";
    			$mail->SMTPDebug = 0;
    			$mail->Debugoutput = 'html';
    			$mail->Host = 'smtp.gmail.com';
    			$mail->Port = '587';
    			$mail->SMTPSecure = 'tls';
    			$mail->SMTPAuth = true;
    			$mail->Username = 'robot2@avtomain.com';
    			$mail->Password ='Cy6JbZHP';
    			$mail->setFrom($smtp_username, 'Автомайн');
    			$mail->addAddress($address);
    			$mail->Subject = $subject;
    			$mail->Body    = $text;
    			$mail->AltBody = $text;
    			$mail->IsHTML(true);
    			$mail->send();
    			$error_send = 0;
    		} catch (Exception $e) {
    			$error_send = $mail->ErrorInfo;
    			$this->writeLog('email', 'Ошибка отправки письма 2'.$mail->ErrorInfo.' на адрес '.$address);
    			return $error_send;
    		}
    		/*$errors_send = $errors + 1;
    		if ($errors_send <= 2) {
    			sendMyMail($subject, $text , $address, $errors_send);
    		} else {
    			return $error_send;
    		}*/
    	}
    }

    public function writeLog($type, $text)
    {
      global $db;
      $sql = "INSERT INTO `logs` SET `type` = ?s, text = ?s";

    	$db->query($sql,$type,$text);
    }

    public function getip()
    {
      if(getenv("HTTP_CLIENT_IP")) {
    		$ip = getenv("HTTP_CLIENT_IP");
    	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
    		$ip = getenv("HTTP_X_FORWARDED_FOR");
    	} else {
    		$ip = getenv("REMOTE_ADDR");
    	}
      $ip = htmlspecialchars(substr($ip,0,15), ENT_QUOTES, '');
      return $ip;
    }

    public function as_md5($key, $string) {
	     $string = md5( $key . md5( 'Z&' . $key . 'x_V' . htmlspecialchars( $string, ENT_QUOTES, '' ) ) );
	     return $string;
   }

   public function cfgRead($cfgName)
   {
      global $db;
      $cfgValue = $db->getOne("SELECT `data` FROM `settings` WHERE `cfgname` = ?s", $cfgName);
      return $cfgValue;
   }

   public function generator($case1, $case2, $case3, $case4, $num1)
   {
      $password = "";

    	$small="abcdefghijklmnopqrstuvwxyz";
    	$large="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    	$numbers="1234567890";
    	$symbols="~!#$%^&*()_+-=,./<>?|:;@";
    	mt_srand((double)microtime()*1000000);

    	for ($i=0; $i<$num1; $i++) {
    		$type = mt_rand(1,4);
    		switch ($type) {
    		case 1:
    			if ($case1 == "on") { $password .= $large[mt_rand(0,25)]; } else { $i--; }
    			break;
    		case 2:
    			if ($case2 == "on") { $password .= $small[mt_rand(0,25)]; } else { $i--; }
    			break;
    		case 3:
    			if ($case3 == "on") { $password .= $numbers[mt_rand(0,9)]; } else { $i--; }
    			break;
    		case 4:
    			if ($case4 == "on") { $password .= $symbols[mt_rand(0,24)]; } else { $i--; }
    			break;
    		}
    	}
	    return $password;
   }

   public function getMonthName($month_num, $case = 'i')
   {
    switch ($month_num) {
    case 1:
      if ($case == 'i') { return 'январь'; } else { return 'января'; }
    break;

    case 2:
      if ($case == 'i') { return 'февраль'; } else { return 'февраля'; }
    break;

    case 3:
      if ($case == 'i') { return 'март'; } else { return 'марта'; }
    break;

    case 4:
      if ($case == 'i') { return 'апрель'; } else { return 'апреля'; }
    break;

    case 5:
      if ($case == 'i') { return 'май'; } else { return 'мая'; }
    break;

    case 6:
      if ($case == 'i') { return 'июнь'; } else { return 'июня'; }
    break;

    case 7:
      if ($case == 'i') { return 'июль'; } else { return 'июля'; }
    break;

    case 8:
      if ($case == 'i') { return 'август'; } else { return 'августа'; }
    break;

    case 9:
      if ($case == 'i') { return 'сентябрь'; } else { return 'сентября'; }
    break;

    case 10:
      if ($case == 'i') { return 'октябрь'; } else { return 'октября'; }
    break;

    case 11:
      if ($case == 'i') { return 'ноябрь'; } else { return 'ноября'; }
    break;

    case 12:
      if ($case == 'i') { return 'декабрь'; } else { return 'декабря'; }
    break;
    }
   }

   public function logout() {
     setcookie('user', null, -1, '/');
     setcookie('user', null, -1, '/cab');
     header("Location: /");
   }

   public function changeBalance($user_id, $balance_type, $operation_type, $amount, $comment = '', $date = '')
   {
     global $db;

     $operation_data = $db->getRow("SELECT * FROM `operations_jornal_types` WHERE id = ?i", $operation_type);

     if ($operation_data['type'] == 'debit') {
       $symbol = '+';
     } else {
       $symbol = '-';
     }

     //меняем баланс
     $db->query("UPDATE purses SET balance = balance ".$symbol." ?s WHERE user_id = ?i AND type = ?i", $amount, $user_id, $balance_type);

     //записываем в журнал
     $insert = [
       'user_id' => $user_id,
       'op_type' => $operation_type,
       'balance_type' => $balance_type,
       'amount' => $symbol.$amount,
       'comment' => $comment
     ];

     if ($date) {
       $insert['date'] = $date;
     }

     $db->query("INSERT INTO operations_jornal SET ?u", $insert);

   }

   function morph($n, $f1, $f2, $f5) {
   	$n = abs(intval($n)) % 100;
   	if ($n>10 && $n<20) return $f5;
   	$n = $n % 10;
   	if ($n>1 && $n<5) return $f2;
   	if ($n==1) return $f1;
   	return $f5;
   }

   public function num2str($num)
   {
   	$nul='ноль';
   	$ten=array(
   		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
   		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
   	);
   	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
   	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
   	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
   	$unit=array( // Units
   		array('копейка' ,'копейки' ,'копеек',	 1),
   		array('рубль'   ,'рубля'   ,'рублей'    ,0),
   		array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
   		array('миллион' ,'миллиона','миллионов' ,0),
   		array('миллиард','милиарда','миллиардов',0),
   	);
   	//
   	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
   	$out = array();
   	if (intval($rub)>0) {
   		foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
   			if (!intval($v)) continue;
   			$uk = sizeof($unit)-$uk-1; // unit key
   			$gender = $unit[$uk][3];
   			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
   			// mega-logic
   			$out[] = $hundred[$i1]; # 1xx-9xx
   			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
   			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
   			// units without rub & kop
   			if ($uk>1) $out[]= $this->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
   		} //foreach
   	}
   	else $out[] = $nul;
   	$out[] = $this->morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
   	$out[] = $kop.' '.$this->morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
   	return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
   }
}
