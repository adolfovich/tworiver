ALTER TABLE  `news` ADD  `preview` VARCHAR( 1000 ) NOT NULL AFTER  `text` ;
ALTER TABLE  `news` ADD  `discussed` INT NOT NULL DEFAULT  '0';