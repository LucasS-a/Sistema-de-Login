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

DELIMITER ;;
CREATE PROCEDURE sp_users_save(
pname VARCHAR(30),
plastName VARCHAR(30), 
plogin VARCHAR(30), 
ppassword VARCHAR(256), 
pemail VARCHAR(128), 
pgender tinyint
)
BEGIN
	
    INSERT INTO tb_users(
        name,
        lastName, 
        login, 
        password, 
        email, 
        gender
    ) VALUES(
        pname,
        plastName, 
        plogin, 
        ppassword, 
        pemail, 
        pgender
    );
    
    SELECT * FROM tb_users WHERE idUser = LAST_INSERT_ID();
    
END ;;
DELIMITER ;


DELIMITER ;;
CREATE PROCEDURE sp_usersupdate_save(
pidUser INT,
pname VARCHAR(30),
plastName VARCHAR(30),
plogin VARCHAR(30),
ppassword VARCHAR(256), 
pemail VARCHAR(128),
pgender INT
)
BEGIN
    
    UPDATE tb_users
    SET
        name = pname,
        lastName = plastName,
        login = plogin,
        password = ppassword,
        email = pemail,
        gender = pgender
	WHERE 
        idUser = pidUser;
    
    SELECT * FROM tb_users WHERE idUser = pidUser;
    
END ;;
DELIMITER ;

DELIMITER ;;
CREATE PROCEDURE sp_users_delete(
pidUser INT
)
BEGIN

    DELETE FROM tb_tokens WHERE idUser = pidUser;
    DELETE FROM tb_users WHERE iduser = pidUser;
    
END ;;
DELIMITER ;