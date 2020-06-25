<?php

if (isset($get['id'])) {
  $result_news = $db->getAll("SELECT * FROM `news` WHERE `id` = ?i", $get['id']);
} else {
  $result_news = $db->getAll("SELECT * FROM `news` WHERE `is_del` = 0 AND `date_end` IS NULL OR `is_del` = 0 AND `date_end` >= '$curdate' ORDER BY `important` DESC, `date_crate` DESC");
}

//var_dump($result_news);

include ('tpl/main/news.tpl');
