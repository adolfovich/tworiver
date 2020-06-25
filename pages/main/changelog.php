<?php

$changelog = $db->getAll("SELECT * FROM `changelog` ORDER BY `id` DESC");

include ('tpl/main/changelog.tpl');
