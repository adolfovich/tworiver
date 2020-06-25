<?php

$result_news = $db->getAll("SELECT * FROM `news` WHERE `is_del` = 0 AND `date_end` IS NULL OR `is_del` = 0 AND `date_end` >= '$curdate' ORDER BY `important` DESC, `date_crate` DESC");

include ('tpl/main/default.tpl');
