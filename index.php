<?php
require_once "config/config.php";

require_once "classes/Base.php";
require_once "classes/Database.php";
require_once "classes/Paste.php";

require_once "lib/geshi/geshi.php";

if (!$Database->isConnected())
	die("Could not connect to the database");

if (isset($_GET['page']))
{
	switch ($_GET['page'])
	{
		case 'paste':
			require_once('pages/paste.php');
			break;
		case 'view':
			require_once('pages/view.php');
			break;
		default:
			require_once('pages/status/404.php');
	}
}
else
	require_once('pages/paste.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="resources/external/bootstrap/css/bootstrap.min.css" />
	<!-- Bootstrap Select -->
	<link rel="stylesheet" type="text/css" href="resources/external/bootstrap-select/css/bootstrap-select.css" />
	<!-- Custom -->
	<link rel="stylesheet" type="text/css" href="resources/css/main.css" />
	<title><?php echo (!empty($title)) ? $config['website'] . " - " . $title : $config['website']; ?></title>
</head>

<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-1"></div>
		<div class="col-sm-10">
			<div class="container-fluid">

				<?php
				show_page();
				?>

			</div>
		</div>
	</div>
</div>

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Tether -->
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<!-- Bootstrap -->
<script src="resources/external/bootstrap/js/bootstrap.min.js"></script>
<!-- Bootstrap Select -->
<script src="resources/external/bootstrap-select/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
    $('.selectpicker').selectpicker({
      });
</script>

</body>

</html>