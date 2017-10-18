<?php

function show_page()
{
	global $Paste;

	if (!isset($_GET['pid']))
		echo "ERROR: Invalid paste ID";
	else if (!$Paste->loadFromId($_GET['pid']))
		echo "ERROR: Could't load paste: " . $Paste->getErrorStr();
	else
		echo $Paste->getContent();
}