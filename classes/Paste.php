<?php

class Paste extends Base
{
	private $title;
	private $expiration_epoch;
	private $auto_destroy;
	private $syntax_highligting;
	private $content;
	private $access;

	public function publishPaste()
	{
		try
		{
			$stmt = $this->sqlres->prepare("INSERT INTO `paste` (title, expiration_epoch, autodestroy, syntax_highlightinh, content, access) VALUES (:title, :expiration_epoch, :autodestroy, syntax_highlighting, :content, :access);");
			$stmt->execute(array(
				':title' => $title,
				':expiration_epoch' => $expiration_epoch,
				':autodestroy' => $autodestroy,
				':syntax_highlighting' => $syntax_highlighting,
				':content' => $content,
				':access' => $access
			));
		}
		catch (PDOException $e)
		{
			$this->setErrorStr("Paste Publish: SQL Request Failed, please contant the system adminsitrator");
			return false;
		}
		return true;
	}

	public function loadPasteFromPost(&$post)
	{
		$this->title = (isset($post['paste_title']) && !empty($post['paste_title'])) ? htmlspecialchars($post['title']) : "Without Title";
		$this->expiration_epoch = $this->expirationToTimeStamp($post['expiration']);
		$this->auto_destroy = isset($post['paste_autodestroy']);
		$this->syntax_highlighting = $post['paste_language'];
		$this->content = htmlspecialchars($post['paste_content']);
		$this->access = $post['paste_access'];
	}

	public function isPastePostValid(&$post)
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
		if (!isset($post['paste_content']) || empty($post['paste_content']))
		{
			$this->setErrorStr("Paste Content: The content of the paste musn't be empty");
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
		if (!isset($post['paste_access']) || ($post['paste_access'] != "accessfree" || $post['paste_access'] != "accesspass" || $post['paste_access'] != "accessip"))
		{
			$this->setErrorStr("Access: Couldn't retrieve valid data in the posted form");
			return false;
		}
	}

	public function isPastePosted(&$post)
	{
		return (isset($post['paste_post']));
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
			return (time() + $timeToAdd);
		}
	}
}

$Paste = new Paste();
$Paste->setConfig($config);
$Paste->setSqlres($Database->getSqlres());