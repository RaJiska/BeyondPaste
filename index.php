<?php
require_once "config/config.php";

require_once "classes/Base.php";
require_once "classes/Database.php";
require_once "classes/Page.php";
require_once "classes/Paste.php";

require_once "lib/geshi/geshi.php";

if (!$Database->isConnected())
	die("Could not connect to the database");

$Page->retrieve((isset($_GET['page']) ? $_GET['page'] : null));
$Page->display();