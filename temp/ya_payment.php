<?php


if (isset($_REQUEST)) {
  $fd = fopen("log.txt", 'w') or die("не удалось создать файл");
  //var_dump($fd);
  foreach ($_REQUEST as $key => $value) {
    fwrite($fd, $key.' => '.$value."\r\n");
  }
  fclose($fd);
}
