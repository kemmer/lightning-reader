CREATE SCHEMA `lightning_reader` DEFAULT CHARACTER SET utf8mb4;

CREATE USER 'reader'@'localhost' IDENTIFIED BY '123456';
GRANT ALL ON lightning_reader.* TO 'reader'@'localhost';

CREATE TABLE `lightning_reader`.`file_info` (
  `id`             INT UNSIGNED   AUTO_INCREMENT,
  `name`           VARCHAR(200)   NOT NULL,
  `last_line`      BIGINT         NOT NULL DEFAULT 0,
  `lines_read`     BIGINT         NOT NULL DEFAULT 0,
  `lines_failed`   BIGINT         NOT NULL DEFAULT 0,

  PRIMARY KEY (`id`)
);

CREATE TABLE `lightning_reader`.`request` (
  `id`             INT UNSIGNED   AUTO_INCREMENT,
  `file_info_id`   INT UNSIGNED   NOT NULL,
  `service`        VARCHAR(100)   NOT NULL,
  `when`           DATETIME       NOT NULL,
  `details`        VARCHAR(100)   NOT NULL,
  `http_code`      SMALLINT       NOT NULL,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`file_info_id`) REFERENCES file_info (`id`)
);

CREATE TABLE `lightning_reader`.`request_errors` (
  `id`             INT UNSIGNED   AUTO_INCREMENT,
  `file_info_id`   INT UNSIGNED   NOT NULL,
  `content`        TEXT           NOT NULL,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`file_info_id`) REFERENCES file_info (`id`)
);

