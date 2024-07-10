CREATE USER 'sc'@'localhost' IDENTIFIED BY '';
CREATE DATABASE sc;
GRANT ALL ON sc.* TO 'sc'@'localhost';

USE sc;

CREATE TABLE users (
  id int AUTO_INCREMENT PRIMARY KEY,
  username varchar(30),
  hash text
);

CREATE TABLE categories (
  id int AUTO_INCREMENT PRIMARY KEY,
  name text
);

CREATE TABLE sites (
  id int AUTO_INCREMENT PRIMARY KEY,
  cateId int,
  name text,
  href text,
  FOREIGN KEY (cateId) REFERENCES categories(id)
);

INSERT INTO users (username, hash) VALUES ('', "");
INSERT INTO categories (name) VALUES ("Bez kategorii");
