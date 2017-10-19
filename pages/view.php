<?php

function show_page()
{
	global $Paste;
	$paste_data = array();
	$paste_content = null;

	if (pasteView($Paste, $paste_data))
		$paste_content = $Paste->geshiParse();
	else
		$paste_content = $Paste->getErrorStr();

	?>
	
	<script>
		var raw_link = '<?php echo "?page=raw" . (($paste_data['id'] != null) ? "&pid=" . $paste_data['id'] : ""); ?>';
	</script>

	<?php
	pasteViewDesign($paste_data, $paste_content);
}

function pasteViewDesign(&$paste_data, &$paste_content)
{
	?>
	<div class="mt-3">
		<div class="row no-gutters">
			<div class="col-3">
				<h2><?php echo $paste_data['title'] ?></h2>
			</div>

			<div class="col-5"></div>

			<div class="col-2 pr-2">
				<button type="button" class="btn btn-secondary btn-block" onclick="window.location=raw_link">
					<span class="pr-1">
						<img src="resources/external/octicons/img/grabber.svg" width=16 height=32 onerror="this.src='lib/octicons/grabber.png'">
					</span>
					RAW
				</button>
			</div>

			<div class="col-2 pl-2">
				<button type="button" class="btn btn-secondary btn-block" onclick="window.location='?page=paste'">
					<span class="pr-1">
						<img src="resources/external/octicons/img/file.svg" width=16 height=32 onerror="this.src='lib/octicons/grabber.png'">
					</span>
					NEW PASTE
				</button>
			</div>
		</div>
	</div>

	<hr>

	<div id="geshicode">
		<?php
		echo $paste_content;
		?>
	</div>
	<?php
}

function pasteView(&$Paste, &$paste_data)
{
	$paste_data['id'] = (isset($_GET['pid'])) ? $_GET['pid'] : null;
	if (!$Paste->loadFromId($paste_data['id']) || !($paste_data['geshi_parsed'] = $Paste->geshiParse()))
	{
		$paste_data['title'] = "Oops... :(";
		return false;
	}
	$paste_data['title'] = $Paste->getTitle() . " - #" . $Paste->getId();
	return true;
}

