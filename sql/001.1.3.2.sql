ALTER TABLE  `acts` CHANGE  `date`  `date_start` DATE NOT NULL ;
ALTER TABLE  `acts` ADD  `date_end` DATE NOT NULL AFTER  `date_start` ;