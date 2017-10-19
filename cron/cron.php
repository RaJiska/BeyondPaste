<?php
require_once "../config/config.php";

require_once "../classes/Base.php";
require_once "../classes/Database.php";

$Database->connect();
if (!$Database->isConnected())
	die("Could not connect to the database");

$current_epoch = time();
$stmt = $Database->getSqlres()->query("UPDATE `paste` SET deleted = '1' WHERE $current_epoch >= expiration_epoch;");