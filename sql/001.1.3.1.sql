ALTER TABLE  `acts` ADD  `type` INT NOT NULL ;

CREATE TABLE IF NOT EXISTS `acts_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `acts_type` ADD  `link` VARCHAR( 255 ) NOT NULL ;

INSERT INTO `acts_type` (`id`, `name`) VALUES (NULL, 'Электричество');
INSERT INTO `acts_type` (`id`, `name`) VALUES (NULL, 'Членские взносы');
INSERT INTO `acts_type` (`id`, `name`) VALUES (NULL, 'Целевые взносы');

UPDATE  `acts_type` SET  `link` =  'forms/act_reconciliation.php' WHERE  `acts_type`.`id` =1;
UPDATE  `acts_type` SET  `link` =  'forms/act_reconciliation_member.php' WHERE  `acts_type`.`id` =2;
UPDATE  `acts_type` SET  `link` =  'forms/act_reconciliation_target.php' WHERE  `acts_type`.`id` =3;