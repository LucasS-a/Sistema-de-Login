CREATE DATABASE  IF NOT EXISTS db_bitwise /*!40100 DEFAULT CHARACTER SET utf8 */;

USE db_bitwise;

DROP TABLE IF EXISTS tb_users;
CREATE TABLE tb_users (
    idUser INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    lastName VARCHAR(30) NOT NULL,
    email VARCHAR(128) UNIQUE DEFAULT NULL,
    login varchar(30) UNIQUE NOT NULL,
    password varchar(256) NOT NULL,
    gender TINYINT NOT NULL,
    dtRegister timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (idUser)
);

DROP TABLE IF EXISTS tb_tokens;
CREATE TABLE tb_tokens (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idUser INT UNSIGNED NOT NULL,
    token VARCHAR(1000) NOT NULL,
    refreshToken VARCHAR(1000) NOT NULL,
    expiredAt DATETIME NOT NULL,
    active TINYINT UNSIGNED NOT NULL DEFAULT 1,
    CONSTRAINT fk_tokens_tb_users 
        FOREIGN KEY (idUser) REFERENCES tb_users(idUser)
);