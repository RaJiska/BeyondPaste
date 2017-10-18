<?php

class Paste extends Base
{
	private $title = null;
	private $owner_ip = null;
	private $expiration_epoch = null;
	private $autodestroy = null;
	private $syntax_highligting = null;
	private $content = null;
	private $access = null;
	private $views = null;
	private $deleted = null;

	private $is_published = false;
	private $id = 0;

	public function publish()
	{
		try
		{
			$stmt = $this->sqlres->prepare("INSERT INTO `paste` (title, owner_ip, expiration_epoch, autodestroy, syntax_highlighting, content, access_id) VALUES (:title, INET_ATON(:owner_ip), :expiration_epoch, :autodestroy, :syntax_highlighting, :content, :access_id);");
			$stmt->execute(array(
				':title' => $this->title,
				':owner_ip' => $this->owner_ip,
				':expiration_epoch' => $this->expiration_epoch,
				':autodestroy' => $this->autodestroy,
				':syntax_highlighting' => $this->syntax_highlighting,
				':content' => $this->content,
				':access_id' => $this->access
			));
		}
		catch (PDOException $e)
		{
			$this->setErrorStr("Paste Publish: SQL Request Failed, please contant the system adminsitrator");
			return false;
		}

		$this->is_published = true;
		$this->id = $this->sqlres->lastInsertId();
		return true;
	}

	public function loadFromPost(&$post)
	{
		$this->title = (isset($post['paste_title']) && !empty($post['paste_title'])) ? htmlspecialchars($post['paste_title']) : "Without Title";
		$this->owner_ip = $_SERVER['REMOTE_ADDR'];
		$this->expiration_epoch = $this->expirationToTimeStamp($post['expiration']);
		$this->autodestroy = isset($post['paste_autodestroy']);
		$this->syntax_highlighting = $post['paste_language'];
		$this->content = htmlspecialchars($post['paste_content']);
		$this->access = $post['paste_access'];
		$this->views = 0;
		$this->deleted = false;
	}

	public function isPostValid(&$post)
	{
		if (!isset($post['paste_expiration']) || 
			($post['paste_expiration'] != "1h" && $post['paste_expiration'] != "1d" &&
			$post['paste_expiration'] != "1w" && $post['paste_expiration'] != "2w" && 
			$post['paste_expiration'] != "1m" && $post['paste_expiration'] != "6m" && 
			$post['paste_expiration'] != "1y" && $post['paste_expiration'] != "never"))
		{
			$this->setErrorStr("Expiration: Couldn't retrieve valid data in the posted form");
			return false;
		}
		if (isset($post['paste_title']) && strlen($post['paste_title']) > 50)
		{
			$this->setErrorStr("Paste Title: String is too long, 50 characters maximum");
			return false;
		}
		if (!isset($post['paste_content']) || empty($post['paste_content']))
		{
			$this->setErrorStr("Paste Content: The content of the paste musn't be empty");
			return false;
		}
		if (strlen($post['paste_content']) > 65000)
		{
			$this->setErrorStr("Paste Content: The paste is too large");
			return false;
		}
		if (!isset($post['paste_language']))
		{
			$this->setErrorStr("Syntax Highlighting: Couldn't retrieve language data");
			return false;
		}
		$Geshi = new Geshi("Hello World", $post['paste_language']);
		if ($Geshi->error())
		{
			$this->setErrorStr("Syntax Highlighting: Couldn't set syntax highlighting");
			return false;
		}
		if (!isset($post['paste_access']) || ($post['paste_access'] != "accessfree" && $post['paste_access'] != "accesspass" && $post['paste_access'] != "accessip"))
		{
			$this->setErrorStr("Access: Couldn't retrieve valid data in the posted form");
			return false;
		}
		return true;
	}

	public function isPosted(&$post)
	{
		return (isset($post['paste_post']));
	}

	public function getIsPublished()
	{
		return ($this->is_published);
	}

	public function getId()
	{
		return ($this->$id);
	}

	public function loadFromId($id)
	{
		$row = false;

		if (!is_numeric($id) || $id < 1)
		{
			$this->setErrorStr("Paste ID must be a positive number");
			return false;
		}
		try
		{
			$stmt = $this->sqlres->prepare("SELECT * FROM paste WHERE id = ':id' LIMIT 1;");
			$stmt->execute(array(":id" => $id));
			if (!($row = $stmt->fetch(PDO::FETCH_ASSOC)))
				throw new Exception();
		}
		catch (Exception $e)
		{
			$this->setErrorStr("Could not retrieve given paste");
			return false;
		}

		$this->title = $row['title'];
		$this->owner_ip = $row['owner_ip'];
		$this->expiration_epoch = $row['expiration_epoch'];
		$this->autodestroy = $row['autodestroy'];
		$this->syntax_highligting = $row['syntax_highlighting'];
		$this->content = $row['content'];
		$this->access = $row['access'];
		$this->views = $row['views'];
		$this->deleted = $row['deleted'];

		$this->is_published = true;
		$this->id = $row['id'];
	}

	public function discard()
	{
		$this->title = null;
		$this->owner_ip = null;
		$this->expiration_epoch = null;
		$this->autodestroy = null;
		$this->syntax_highligting = null;
		$this->content = null;
		$this->access = null;
		$this->views = null;
		$this->deleted = null;

		$this->is_published = false;
		$this->id = 0;
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
}

$Paste = new Paste();
$Paste->setConfig($config);
$Paste->setSqlres($Database->getSqlres());