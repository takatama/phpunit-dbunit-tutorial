CREATE DATABASE IF NOT EXISTS tutorial DEFAULT CHARACTER SET utf8;
CREATE USER 'dbunit'@'localhost';
GRANT ALL ON tutorial.* to 'dbunit'@'localhost';
CREATE TABLE IF NOT EXISTS tutorial.account (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100),
    PRIMARY KEY (id)
);
CREATE TABLE IF NOT EXISTS tutorial.bookmark (
    id INT NOT NULL AUTO_INCREMENT,
    url VARCHAR(100),
    account_id INT,
    created TIMESTAMP DEFAULT NOW(),
    PRIMARY KEY (id)
);

