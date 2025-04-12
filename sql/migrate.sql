ALTER TABLE `settings` ADD `input_type` VARCHAR(255) NOT NULL DEFAULT 'text' AFTER `description`, ADD `options` TEXT NULL DEFAULT NULL AFTER `input_type`;

UPDATE `settings` SET `input_type` = 'select', `options` = '0,\'Выкл\';1,\'Вкл\'' WHERE `settings`.`id` = 25;

CREATE TABLE `snt_v2`.`Indications_log` (`id` INT NOT NULL AUTO_INCREMENT , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `log` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `Indications_log` CHANGE `log` `log` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL;

ALTER TABLE `Indications` ADD `datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `id`;

ALTER TABLE `Indications_log` ADD `period` TEXT NOT NULL AFTER `date`;

