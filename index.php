<?php
require_once "config/config.php";
require_once "classes/Database.php";

$db = new Database($config);
if ($db->res == null)
    die("Could not connect to the database");


?>