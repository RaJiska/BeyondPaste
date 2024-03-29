<?php

function show_page()
{
	global $Paste;
	$paste_data = array();
	$error_str = null;

	if (pasteView($Paste, $paste_data, $error_str))
		$paste_data['content'] = $Paste->geshiParse();
	else
		$paste_data['content'] = $error_str;

	$pid = ((isset($_GET['pid'])) ? $_GET['pid'] : "");
	$url_token = ((isset($_GET['token'])) ? "?token=" . $_GET['token'] : "");

	?>
	<script>
		var raw_link = '<?php echo "/" . $pid . "/raw" . $url_token; ?>';
	</script>
	<?php

	pasteViewDesign($paste_data);
}

function pasteViewDesign(&$paste_data)
{
	?>
	<div class="mt-3">
		<div class="row no-gutters">
			<div class="col-6">
				<h2><?php echo $paste_data['title'] ?></h2>
			</div>

			<div class="col-2"></div>

			<div class="col-2 pr-2">
				<button type="button" class="btn btn-secondary btn-block" onclick="window.location=raw_link">
					<span class="pr-1">
						<img src="/resources/external/octicons/img/grabber.svg" width=16 height=32>
					</span>
					RAW
				</button>
			</div>

			<div class="col-2 pl-2">
				<button type="button" class="btn btn-secondary btn-block" onclick="window.location='/'">
					<span class="pr-1">
						<img src="/resources/external/octicons/img/file.svg" width=16 height=32>
					</span>
					NEW PASTE
				</button>
			</div>
		</div>
	</div>

	<hr>

	<?php
	if ($paste_data['destroy'])
	{
	?>
		<div class="alert alert-info" role="alert">
			<img src="/resources/external/octicons/img/info.svg" width=16 height=32>
			This paste is set to destroy upon the first read. Refreshing the page, viewing the paste raw, and any other action that may require your page to reload would result in the paste deleting itself.
		</div>
	<?php
	}
	?>

	<div id="geshicode">
		<?php
		echo $paste_data['content'];
		?>
	</div>
	<?php
}

function pasteView(&$Paste, &$paste_data, &$error_str)
{
	$paste_data['id'] = (isset($_GET['pid'])) ? $_GET['pid'] : null;
	$paste_data['destroy'] = false;

	$paste_data['title'] = "Oops... :(";
	if (!$Paste->loadFromId($paste_data['id']) || !($paste_data['geshi_parsed'] = $Paste->geshiParse()))
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
	$paste_data['title'] = htmlspecialchars($Paste->getTitle()) . " - #" . $Paste->getId();
	if ($paste_data['destroy'] && (
		($_SERVER['REMOTE_ADDR'] == $Paste->getOwnerIp() && (time() >= $Paste->getCreationEpoch() + 15)) ||
		$_SERVER['REMOTE_ADDR'] != $Paste->getOwnerIp()))
	{
		$Paste->setDeleted(true);
		$Paste->update();
	}
	return true;
}

