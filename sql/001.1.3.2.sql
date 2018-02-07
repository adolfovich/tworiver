ALTER TABLE  `acts` CHANGE  `date`  `date_start` DATE NOT NULL ;
ALTER TABLE  `acts` ADD  `date_end` DATE NOT NULL AFTER  `date_start` ;
ALTER TABLE  `users` ADD  `modem_num` VARCHAR( 255 ) NOT NULL AFTER  `sch_step` ,
ADD INDEX (  `modem_num` ) ;
ALTER TABLE  `users` CHANGE  `modem_num`  `modem_num` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
UPDATE `users` SET `modem_num` = NULL;

ALTER TABLE  `tarifs` ADD  `id_waviot` VARCHAR( 255 ) NOT NULL AFTER  `id` ,
ADD INDEX (  `id_waviot` ) ;


ALTER TABLE  `Indications` ADD  `auto` INT NOT NULL,
ADD INDEX (  `auto` );
ALTER TABLE  `Indications` ADD  `prev_indications` decimal(10,2) NOT NULL AFTER `Indications`;