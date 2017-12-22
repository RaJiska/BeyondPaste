<?php

class Page Extends Base
{
	private $title = "";
	private $output_raw = false;
	private $page_path = null;

	public function display()
	{
		require_once($this->page_path);

		if (!$this->output_raw)
		{
			$headerTitle = (!empty($this->title)) ? $this->config['website'] . " - " . $this->title : $this->config['website'];
			echo("
				<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
				<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
				<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" />
				<link rel=\"stylesheet\" type=\"text/css\" href=\"resources/external/bootstrap/css/bootstrap.min.css\" />
				<link rel=\"stylesheet\" type=\"text/css\" href=\"resources/external/bootstrap-select/css/bootstrap-select.css\" />
				<link rel=\"stylesheet\" type=\"text/css\" href=\"resources/external/geshicss/css/dawn.css\" />
				<link rel=\"stylesheet\" type=\"text/css\" href=\"resources/css/main.css\" />
				<title>$headerTitle</title>
				</head>
				<body>
				<div class=\"container-fluid\">
				<div class=\"row\">
				<div class=\"col-sm-2\"></div>
				<div class=\"col-sm-8\">
				<div class=\"container-fluid pt-4\">
			");
		}
		else
			header("Content-Type: text/plain");

		show_page();

		if (!$this->output_raw)
		{
			echo("
				<div class=\"col-sm-2\"></div>
				</div>
				</div>
				</div>
				</div>

				<div class=\"footer\">
				<center>Powered by <a href=\"https://github.com/RaJiska/BeyondPaste\">BeyondPaste</a> <img src=\"resources/external/octicons/img/octoface.svg\" width=12 height=24 onerror=\"this.src='lib/octicons/eye.png'\">. Copyright Doriann \"Ra'Jiska\" Corlouër ©</center>
				</div>

				<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>
				<script src=\"https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js\"></script>
				<script src=\"resources/external/bootstrap/js/bootstrap.min.js\"></script>
				<script src=\"resources/external/bootstrap-select/js/bootstrap-select.min.js\"></script>
				<script src=\"resources/js/beyondpaste.js\"></script>
				<script type=\"text/javascript\">
				$(\'.selectpicker\').selectpicker({ });
				</script>
				</body>
				</html>
			");
		}
	}

	public function retrieve($page)
	{
		switch ($page)
		{
		case 'paste':
		case null:
			$this->title = '';
			$this->page_path = 'pages/paste.php';
			break;
		case 'view':
			$this->title = 'View Paste';
			$this->page_path = 'pages/view.php';
			break;
		case 'raw':
			$this->title = 'Raw Paste';
			$this->output_raw = true;
			$this->page_path = 'pages/raw.php';
			break;
		default:
			$this->title = '404';
			$this->page_path = 'pages/status/404.php';
		}
	}

	/* Getters / Setters */

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}
}

$Page = new Page();
$Page->setConfig($config);