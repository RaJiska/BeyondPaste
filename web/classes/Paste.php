<?php

class Paste extends Base
{
	private $id = null;
	private $title = null;
	private $owner_ip = null;
	private $creation_epoch = null;
	private $expiration_epoch = null;
	private $autodestroy = 0;
	private $syntax_highlighting = null;
	private $content = null;
	private $Access = null;
	private $views = 0;
	private $deleted = 0;

	private $is_loaded = false;
	private $is_published = false;

	public function publish()
	{
		try
		{
			$queries = array(
				"INSERT INTO `access` (type, parameter) VALUES (?, ?);",
				"INSERT INTO `paste` (title, owner_ip, creation_epoch, expiration_epoch, autodestroy, syntax_highlighting, content, access_id, views, deleted) VALUES (?, INET_ATON(?), ?, ?, ?, ?, ?, LAST_INSERT_ID(), ?, ?);"
			);
			$binds = (array(
				array(
					$this->Access->getType(),
					$this->Access->getParameter()
				),
				array(
					$this->title,
					$this->owner_ip,
					$this->creation_epoch,
					$this->expiration_epoch,
					$this->autodestroy,
					$this->syntax_highlighting,
					$this->content,
					$this->views,
					$this->deleted
				)
			));

			$this->id = $this->Database->executeTransaction($queries, $binds, true);
		}
		catch (PDOException $e)
		{
			error_log("Failed publishing: " . $e->getMessage());
			$this->setErrorStr("Paste Publish: Request Failed");
			return false;
		}

		$this->is_published = true;
		return true;
	}

	public function update()
	{
		if (!$this->is_published)
			return false;

		try
		{
			$stmt = $this->Database->prepare("UPDATE `paste` SET title = ?, owner_ip = INET_ATON(?), creation_epoch = ?, expiration_epoch = ?, autodestroy = ?, syntax_highlighting = ?, content = ?, views = ?, deleted = ? WHERE id = ?;");
			$stmt->execute(array(
				$this->title,
				$this->owner_ip,
				$this->creation_epoch,
				$this->expiration_epoch,
				$this->autodestroy,
				$this->syntax_highlighting,
				$this->content,
				$this->views,
				$this->deleted,
				$this->id
			));
		}
		catch (PDOException $e)
		{
			$this->setErrorStr("Paste Update: Request Failed");
			return false;
		}

		return true;
	}

	public function delete()
	{
		if (!$this->is_published)
			return false;

		try
		{
			$queries = array(
				"DELETE FROM `access` WHERE id = (SELECT access_id FROM `paste` WHERE id = ?);",
				"DELETE FROM `paste` WHERE id = ?;"
			);
			$binds = array(
				array($this->id),
				array($this->id)
			);

			$this->Database->executeTransaction($queries, $binds);
		}
		catch (PDOException $e)
		{
			$this->setErrorStr("Paste Delete: Request Failed");
			return false;
		}

		$this->discard();
		return true;
	}

	public function geshiParse()
	{
		if (!$this->is_loaded)
			return false;

		$Geshi = new Geshi($this->content, $this->syntax_highlighting);
		if ($Geshi->error())
		{
			$this->setErrorStr("Could not parse given paste");
			return false;
		}
		$Geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
		return $Geshi->parse_code();
	}

	public function loadFromPost()
	{
		if (!isset($_POST))
			return;
		$this->title = (isset($_POST['paste_title']) && !empty($_POST['paste_title'])) ? $_POST['paste_title'] : "Without Title";
		$this->owner_ip = $_SERVER['REMOTE_ADDR'];
		$this->creation_epoch = time();
		$this->expiration_epoch = $this->expirationToTimeStamp($_POST['paste_expiration']);
		$this->autodestroy = isset($_POST['paste_autodestroy']) ? 1 : 0;
		$this->syntax_highlighting = $_POST['paste_language'];
		$this->content = $_POST['paste_content'];
		$this->views = 0;
		$this->deleted = 0;

		$this->is_loaded = true;
		$this->is_published = false;
		$this->Access = new Access($_POST['paste_access']);
	}

	public function isPostValid()
	{
		if (!isset($_POST, $_POST['paste_expiration']))
			return false;
		if (($_POST['paste_expiration'] != "1h" && $_POST['paste_expiration'] != "1d" &&
			$_POST['paste_expiration'] != "1w" && $_POST['paste_expiration'] != "2w" && 
			$_POST['paste_expiration'] != "1m" && $_POST['paste_expiration'] != "6m" && 
			$_POST['paste_expiration'] != "1y" && $_POST['paste_expiration'] != "never"))
		{
			$this->setErrorStr("Couldn't retrieve valid expiration data in the posted form");
			return false;
		}
		if (isset($_POST['paste_title']) && strlen($_POST['paste_title']) > 50)
		{
			$this->setErrorStr("The title is too long, 50 characters maximum");
			return false;
		}
		if (!isset($_POST['paste_content']) || empty($_POST['paste_content']))
		{
			$this->setErrorStr("The content of the paste musn't be empty");
			return false;
		}
		if (strlen($_POST['paste_content']) > 65000)
		{
			$this->setErrorStr("The paste is too large");
			return false;
		}
		if (!isset($_POST['paste_language']))
		{
			$this->setErrorStr("Couldn't retrieve syntax highlighting data");
			return false;
		}
		$Geshi = new Geshi("Hello World", $_POST['paste_language']);
		if ($Geshi->error())
		{
			$this->setErrorStr("Couldn't set syntax highlighting");
			return false;
		}
		if (!isset($_POST['paste_access']) || ($_POST['paste_access'] != ACCESS_FREE && $_POST['paste_access'] != ACCESS_UNLISTED))
		{
			$this->setErrorStr("Couldn't retrieve valid access data in the posted form");
			return false;
		}
		return true;
	}

	public function isPosted()
	{
		return (isset($_POST, $_POST['paste_post']));
	}

	public function loadFromId($id)
	{
		$row = false;

		if (!is_numeric($id) || $id < 1)
		{
			$this->setErrorStr("Paste ID must be a positive number: " . $id);
			return false;
		}

		try
		{
			$stmt = $this->Database->prepare("SELECT paste.id, title, INET_NTOA(owner_ip) AS owner_ip, creation_epoch, expiration_epoch, autodestroy, syntax_highlighting, content, views, deleted, access.type AS access_type, access.parameter AS access_parameter FROM paste LEFT JOIN access ON paste.access_id = access.id WHERE paste.id = ? AND deleted = '0' LIMIT 1;");
			$stmt->execute(array($id));
			if (!($row = $stmt->fetch()))
				throw new PDOException();
		}
		catch (PDOException $e)
		{
			$this->setErrorStr("Could not retrieve given paste");
			return false;
		}

		$this->id = $row['id'];
		$this->title = $row['title'];
		$this->owner_ip = $row['owner_ip'];
		$this->creation_epoch = $row['creation_epoch'];
		$this->expiration_epoch = $row['expiration_epoch'];
		$this->autodestroy = $row['autodestroy'];
		$this->syntax_highlighting = $row['syntax_highlighting'];
		$this->content = $row['content'];
		$this->views = $row['views'];
		$this->deleted = $row['deleted'];

		$this->is_loaded = true;
		$this->is_published = true;
		$this->Access = new Access($row['access_type'], $row['access_parameter']);

		return true;
	}

	public function discard()
	{
		$this->id = null;
		$this->title = null;
		$this->owner_ip = null;
		$this->creation_epoch = null;
		$this->expiration_epoch = null;
		$this->autodestroy = 0;
		$this->syntax_highlighting = null;
		$this->content = null;
		$this->Access = null;
		$this->views = 0;
		$this->deleted = 0;

		$this->is_loaded = false;
		$this->is_published = false;
	}

	public function getPasteLink()
	{
		if (!$this->is_loaded)
			return "";
		return ($this->id . $this->Access->paramToLink());
	}

	private function expirationToTimestamp(&$expiration)
	{
		$time = time();
		$timeToAdd = 0;

		switch ($expiration)
		{
		case '1h':
			$timeToAdd = (60 * 60);
			break;
		case '1d':
			$timeToAdd = (24 * 60 * 60);
			break;
		case '1w':
			$timeToAdd = (7 * 24 * 60 * 60);
			break;
		case '2w':
			$timeToAdd = (14 * 24 * 60 * 60);
			break;
		case '1m':
			$timeToAdd = (30 * 24 * 60 * 60);
			break;
		case '6m':
			$timeToAdd = (6 * 30 * 24 * 60 * 60);
			break;
		case '1y':
			$timeToAdd = (12 * 30 * 24 * 60 * 60);
			break;
		case 'never':
			$timeToAdd = -($time);
		}
		
		return ($time + $timeToAdd);
	}

	/* Getters / Setters */

	public function getId()
	{
		return $this->id;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getOwnerIp()
	{
		return $this->owner_ip;
	}

	public function getCreationEpoch()
	{
		return $this->creation_epoch;
	}

	public function getExpirationEpoch()
	{
		return $this->expiration_epoch;
	}

	public function getAutodestroy()
	{
		return $this->autodestroy;
	}

	public function getSyntaxHighlighting()
	{
		return $this->syntax_highlighting;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getAccess()
	{
		return $this->Access;
	}

	public function getViews()
	{
		return $this->views;
	}

	public function setDeleted($deleted)
	{
		$this->deleted = $deleted;
	}

	public function getDeleted()
	{
		return $this->deleted;
	}

	public function getIsLoaded()
	{
		return $this->is_loaded;
	}
}

$Paste = new Paste();
$Paste->setConfig($config);
$Paste->setDatabase($Database);