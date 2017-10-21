<?php
require_once "../config/config.php";

require_once "../classes/Base.php";
require_once "../classes/Database.php";

$current_epoch = time();
$stmt = $Database->query("UPDATE `paste` SET deleted = '1' WHERE $current_epoch >= expiration_epoch;");