CREATE TABLE paste ( 
    id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
    owner_ip INT UNSIGNED,
    title VARCHAR(50),
    creation_epoch INT(11) UNSIGNED,
    expiration_epoch INT(11) UNSIGNED,
    autodestroy TINYINT(1) NOT NULL DEFAULT 0,
    syntax_highlighting VARCHAR(32),
    content TEXT,
    access_id INT(6) UNSIGNED,
    views INT(10) UNSIGNED DEFAULT 0,
    deleted TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY(id)
);