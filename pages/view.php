<?php

function show_page()
{
	global $Paste;
	$paste_data = array();

	pasteView($Paste, $paste_data);
	pasteViewDesign($paste_data);
}

function pasteViewDesign(&$paste_data)
{
	?>
	<div class="mt-3">
		<div class="row no-gutters">
			<div class="col-3">
				<h2><?php echo $paste_data['title'] ?></h2>
			</div>

			<div class="col-5"></div>

			<div class="col-2 pr-2">
				<button type="button" class="btn btn-secondary btn-block" onclick="window.location='?page=view&raw'">
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

	<div id="paste_geshi"></div>
	<?php
}

function pasteView(&$Paste, &$paste_data)
{
	if (!isset($_GET['pid']) || !$Paste->loadFromId($_GET['pid']) || !($paste_data['geshi_parsed'] = $Paste->geshiParse()))
	{
		$paste_data['title'] = "Oops... :(";
		return false;
	}
	$paste_data['title'] = $Paste->getTitle() . " - #" . $Paste->getId();
	return true;
}

