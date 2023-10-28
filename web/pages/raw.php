<?php

function show_page()
{
	global $Paste;
	$paste_data = array();
	$error_str = null;

	if (pasteRaw($Paste, $paste_data, $error_str))
		echo $Paste->getContent();
	else
		echo "ERROR: " . $error_str;
}

function pasteRaw(&$Paste, &$paste_data, &$error_str)
{
	$paste_data['id'] = (isset($_GET['pid'])) ? $_GET['pid'] : null;
	$paste_data['destroy'] = false;

	if (!$Paste->loadFromId($paste_data['id']))
	{
		
		$error_str = $Paste->getErrorStr();
		return false;
	}

	if (!$Paste->getAccess()->isAllowed())
	{
		$error_str = $Paste->getAccess()->getErrorStr();
		$Paste->discard();
		return false;
	}

	$paste_data['destroy'] = $Paste->getAutodestroy();
	if ($paste_data['destroy'] && (
		($_SERVER['REMOTE_ADDR'] == $Paste->getOwnerIp() && (time() >= $Paste->getCreationEpoch() + 15)) ||
		$_SERVER['REMOTE_ADDR'] != $Paste->getOwnerIp()))
	{
		$Paste->setDeleted(true);
		$Paste->update();
	}
	return true;
}