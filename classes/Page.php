<?php

class Page Extends Base
{
	private $title = "";
	private $output_raw = false;
	private $page_tag = null;
	private $page_path = null;

	public function display()
	{
		require_once($this->page_path);

		if (!$this->output_raw)
		{
			$headerTitle = (!empty($this->title)) ? $this->config['website'] . " - " . $this->title : $this->config['website'];
			echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">");
			echo("<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">");
			echo("<head>");
			echo("<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" />");
			echo("<link rel=\"stylesheet\" type=\"text/css\" href=\"resources/external/bootstrap/css/bootstrap.min.css\" />");
			echo("<link rel=\"stylesheet\" type=\"text/css\" href=\"resources/external/bootstrap-select/css/bootstrap-select.css\" />");
			echo("<link rel=\"stylesheet\" type=\"text/css\" href=\"resources/css/main.css\" />");
			echo("<title>$headerTitle</title>");
			echo("</head>");
			echo("<body>");
			echo("<div class=\"container-fluid\">");
			echo("<div class=\"row\">");
			echo("<div class=\"col-sm-1\"></div>");
			echo("<div class=\"col-sm-10\">");
			echo("<div class=\"container-fluid\">");
		}

		show_page();

		if (!$this->output_raw)
		{
			echo("</div>");
			echo("</div>");
			echo("</div>");
			echo("</div>");
			echo("<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>");
			echo("<script src=\"https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js\"></script>");
			echo("<script src=\"resources/external/bootstrap/js/bootstrap.min.js\"></script>");
			echo("<script src=\"resources/external/bootstrap-select/js/bootstrap-select.min.js\"></script>");
			echo("<script type=\"text/javascript\">");
			echo('$(\'.selectpicker\').selectpicker({ });');
			echo("</script>");
			echo("</body>");
			echo("</html>");
		}
	}

	public function retrieve($page)
	{
		switch ($page)
		{
		case 'paste':
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

	public function getTitle($title)
	{
		return $this->title;
	}
}

$Page = new Page();
$Page->setConfig($config);