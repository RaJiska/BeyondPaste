<?php

class Access extends Base
{
	private $type;
	private $parameter;

	private $data;

	public function __construct($type, $parameter = null)
	{
		$this->type = $type;

		if ($type == ACCESS_UNLISTED && $parameter == null)
			$this->parameter = uniqid();
		else
			$this->parameter = $parameter;
	}

	public function isAllowed()
	{
		switch ($this->type)
		{
		case null:
		case ACCESS_FREE:
			return $this->checkAllowedFree();
			break;
		case ACCESS_UNLISTED:
			return $this->checkAllowedUnlisted();
			break;
		}
		return false;
	}

	public function paramToLink()
	{
		if ($this->type != ACCESS_UNLISTED)
			return "";
		return ("&token=" . $this->parameter);
	}

	private function checkAllowedFree()
	{
		return true;
	}

	private function checkAllowedUnlisted()
	{
		if (!isset($_GET['token']) || $_GET['token'] != $this->parameter)
		{
			$this->setErrorStr("Invalid Token");
			return false;
		}
		return true;
	}

	/* Getters / Setters */

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setParameter($parameter)
	{
		$this->parameter = $parameter;
	}

	public function getParameter()
	{
		return $this->parameter;
	}
}