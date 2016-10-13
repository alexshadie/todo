
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `projects` (
  `id` INT NOT NULL COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_project_user_id_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_project_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
);

CREATE TABLE `astra_todo`.`tasks` (
  `id` INT NOT NULL COMMENT '',
  `title` VARCHAR(255) NULL COMMENT '',
  `status` VARCHAR(1) NOT NULL DEFAULT ' ' COMMENT '',
  `date` DATETIME NOT NULL COMMENT '',
  `time` INT NULL DEFAULT NULL COMMENT '',
  `priority` TINYINT NOT NULL DEFAULT 0 COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  `project_id` INT NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_tasks_user_id_idx` (`user_id` ASC)  COMMENT '',
  INDEX `fk_tasks_project_id_idx` (`project_id` ASC)  COMMENT '',
  CONSTRAINT `fk_tasks_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `astra_todo`.`users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_tasks_project_id`
    FOREIGN KEY (`project_id`)
    REFERENCES `astra_todo`.`projects` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
);
