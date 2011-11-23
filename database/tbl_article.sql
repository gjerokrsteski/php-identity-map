CREATE TABLE IF NOT EXISTS tbl_article (

  id      INT NOT NULL AUTO_INCREMENT,
  title   VARCHAR(30),
  content VARCHAR(255),
  userId  INT NOT NULL,

  PRIMARY KEY (id),

  INDEX (userId),

  FOREIGN KEY (userId)

  REFERENCES tbl_user (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE

) TYPE=INNODB DEFAULT CHARSET=utf8;