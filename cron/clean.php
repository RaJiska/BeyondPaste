<?php
require_once "../config/config.php";

require_once "../classes/Base.php";
require_once "../classes/Database.php";

$current_epoch = time();
$stmt = $Database->query("DELETE FROM `paste` WHERE deleted = '1';");