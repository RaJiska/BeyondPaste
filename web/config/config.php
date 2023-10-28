<?php

require_once "internal_config.php";

/* Name of the website */
$config['website'] = "BeyondPaste";

/* MySQL DB Credentials */
$config['db']['host'] = getenv('MYSQL_HOST');
$config['db']['name'] = getenv('MYSQL_DATABASE');
$config['db']['username'] = getenv('MYSQL_USER');
$config['db']['password'] = getenv('MYSQL_PASSWORD');
