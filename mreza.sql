
DROP DATABASE IF EXISTS mreza;
CREATE DATABASE mreza CHARACTER SET utf16 COLLATE utf16_slovenian_ci;

USE mreza;

CREATE TABLE users (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    pass CHAR(32) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE profiles (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    gender CHAR(1),
    dob DATE,
    user_id INT UNSIGNED UNIQUE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE = InnoDB;

CREATE TABLE followers (
    -- id INT UNSIGNED AUTO_INCREMENT,
    sender_id INT UNSIGNED NOT NULL,
    receiver_id  INT UNSIGNED NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id),
    PRIMARY KEY (sender_id, receiver_id)
) ENGINE = InnoDB;

INSERT INTO users
VALUES 
    (null, "IvanM", MD5("123456")),
    (null, "AnaP", MD5("123456")),
    (null, "BiljanaI", MD5("123456")),
    (null, "MarkoS", MD5("123456")),
    (null, "JelenaJ", MD5("123456"));

INSERT INTO profiles 
VALUES
    (null, 'Ivan', 'Markovic', 'm', '2000-08-15', '1'),
    (null, 'Ana', 'Petrovic', 'z', '1998-12-16', '2'),
    (null, 'Biljana', 'Ilic', 'z', '2002-03-05', '3'),
    (null, 'Marko', 'Savic', 'm', '2000-07-14', '4'),
    (null, 'Jelena', 'Jovic', 'z', '2001-03-03', '5');

INSERT INTO followers 
VALUES
    (1, 2),
    (2, 1),
    (1, 3),
    (2, 4),
    (4, 2),
    (3, 1),
    (5, 2),
    (5, 3),
    (5, 1);