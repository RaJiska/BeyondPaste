CREATE TABLE paste ( 
    id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
    owner_ip INT UNSIGNED,
    title VARCHAR(50),
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiration_epoch INT(1) UNSIGNED,
    autodestroy TINYINT(1) NOT NULL DEFAULT 0,
    syntax_highlighting VARCHAR(32),
    content TEXT,
    access_id INT(6) UNSIGNED,
    views INT(10) UNSIGNED,
    deleted TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY(id)
);