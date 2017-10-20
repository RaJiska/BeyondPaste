CREATE TABLE access (
    id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
    type TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
    parameter VARCHAR(120),
    PRIMARY KEY(id)
);

CREATE TABLE paste ( 
    id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
    owner_ip INT UNSIGNED,
    title VARCHAR(3),
    creation_epoch INT(11) UNSIGNED,
    expiration_epoch INT(11) UNSIGNED,
    autodestroy TINYINT(1) NOT NULL DEFAULT 0,
    syntax_highlighting VARCHAR(32),
    content TEXT NOT NULL,
    access_id INT(6) UNSIGNED,
    views INT(10) UNSIGNED NOT NULL DEFAULT 0,
    deleted TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY(id)
);