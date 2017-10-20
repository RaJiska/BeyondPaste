<?php

class Paste extends Base
{
	private $id = null;
	private $title = null;
	private $owner_ip = null;
	private $creation_epoch = null;
	private $expiration_epoch = null;
	private $autodestroy = null;
	private $syntax_highlighting = null;
	private $content = null;
	private $access = null;
	private $views = null;
	private $deleted = null;

	private $is_loaded = false;
	private $is_published = false;

	public function publish()
	{
		$query = "INSERT INTO `paste` (title, owner_ip, creation_epoch, expiration_epoch, autodestroy, syntax_highlighting, content, access_id, views, deleted) VALUES (?, INET_ATON(?), ?, ?, ?, ?, ?, ?, ?, ?);";
		$bind = array(
			$this->title,
			$this->owner_ip,
			$this->creation_epoch,
			$this->expiration_epoch,
			$this->autodestroy,
			$this->syntax_highlighting,
			$this->content,
			$this->access,
			$this->views,
			$this->deleted
		);
		
		if (!$this->Database->query($query, $bind))
		{
			$this->setErrorStr("Paste Publish: SQL Request Failed");
			return false;
		}

		$this->is_published = true;
		$this->id = $this->sqlres->lastInsertId();
		return true;
	}

	public function update()
	{
		if (!$this->is_published)
			return false;

		$query = "UPDATE `paste` SET title = ?, owner_ip = INET_ATON(?), creation_epoch = ?, expiration_epoch = ?, autodestroy = ?, syntax_highlighting = ?, content = ?, access_id = ?, views = ?, deleted = ? WHERE id = ?;";
		$bind = array(
			$this->title,
			$this->owner_ip,
			$this->creation_epoch,
			$this->expiration_epoch,
			$this->autodestroy,
			$this->syntax_highlighting,
			$this->content,
			$this->access,
			$this->views,
			$this->deleted,
			$this->id
		);

		if (!$this->Database->query($query, $bind))
		{
			$this->setErrorStr("Paste Update: SQL Request Failed");
			return false;
		}

		return true;
	}

	public function delete()
	{
		if (!$this->is_published)
			return false;

		$query = "DELETE FROM `paste` WHERE id = :id;";
		$bind(array($this->id));

		if (!$this->Database->query($query, $bind))
		{
			$this->setErrorStr("Paste Delete: SQL Request Failed");
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

	public function loadFromPost(&$post)
	{
		$this->title = (isset($post['paste_title']) && !empty($post['paste_title'])) ? htmlspecialchars($post['paste_title']) : "Without Title";
		$this->owner_ip = $_SERVER['REMOTE_ADDR'];
		$this->creation_epoch = time();
		$this->expiration_epoch = $this->expirationToTimeStamp($post['paste_expiration']);
		$this->autodestroy = isset($post['paste_autodestroy']);
		$this->syntax_highlighting = $post['paste_language'];
		$this->content = $post['paste_content'];
		$this->access = $post['paste_access'];
		$this->views = 0;
		$this->deleted = false;

		$this->is_loaded = true;
		$this->is_published = false;
	}

	public function isPostValid(&$post)
	{
		if (!isset($post['paste_expiration']) || 
			($post['paste_expiration'] != "1h" && $post['paste_expiration'] != "1d" &&
			$post['paste_expiration'] != "1w" && $post['paste_expiration'] != "2w" && 
			$post['paste_expiration'] != "1m" && $post['paste_expiration'] != "6m" && 
			$post['paste_expiration'] != "1y" && $post['paste_expiration'] != "never"))
		{
			$this->setErrorStr("Couldn't retrieve valid expiration data in the posted form");
			return false;
		}
		if (isset($post['paste_title']) && strlen($post['paste_title']) > 50)
		{
			$this->setErrorStr("The title is too long, 50 characters maximum");
			return false;
		}
		if (!isset($post['paste_content']) || empty($post['paste_content']))
		{
			$this->setErrorStr("The content of the paste musn't be empty");
			return false;
		}
		if (strlen($post['paste_content']) > 65000)
		{
			$this->setErrorStr("The paste is too large");
			return false;
		}
		if (!isset($post['paste_language']))
		{
			$this->setErrorStr("Couldn't retrieve syntax highlighting data");
			return false;
		}
		$Geshi = new Geshi("Hello World", $post['paste_language']);
		if ($Geshi->error())
		{
			$this->setErrorStr("Couldn't set syntax highlighting");
			return false;
		}
		if (!isset($post['paste_access']) || ($post['paste_access'] != "accessfree" && $post['paste_access'] != "accesspass" && $post['paste_access'] != "accessip"))
		{
			$this->setErrorStr("Couldn't retrieve valid access data in the posted form");
			return false;
		}
		return true;
	}

	public function isPosted(&$post)
	{
		return (isset($post['paste_post']));
	}

	public function loadFromId($id)
	{
		$row = false;

		if (!is_numeric($id) || $id < 1)
		{
			$this->setErrorStr("Paste ID must be a positive number");
			return false;
		}

		$query = "SELECT id, title, INET_NTOA(owner_ip) AS owner_ip, creation_epoch, expiration_epoch, autodestroy, syntax_highlighting, content, access_id, views, deleted FROM paste WHERE id = ? AND deleted = '0' LIMIT 1;";
		$bind = array($id);

		if (!$this->Database->query($query, $bind) || !($row = $this->Database->fetch()))
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
		$this->access = $row['access_id'];
		$this->views = $row['views'];
		$this->deleted = $row['deleted'];

		$this->is_loaded = true;
		$this->is_published = true;

		return true;
	}

	public function discard()
	{
		$this->id = null;
		$this->title = null;
		$this->owner_ip = null;
		$this->creation_epoch = null;
		$this->expiration_epoch = null;
		$this->autodestroy = null;
		$this->syntax_highlighting = null;
		$this->content = null;
		$this->access = null;
		$this->views = null;
		$this->deleted = null;

		$this->is_loaded = false;
		$this->is_published = false;
	}

	private function expirationToTimestamp(&$expiration)
	{
		$timeToAdd = 0;

		switch ($expiration)
		{
		case '1h':
			$timeToAdd = (60 * 60);
			breal;
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
		}
		
		return (time() + $timeToAdd);
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
		return $this->access;
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