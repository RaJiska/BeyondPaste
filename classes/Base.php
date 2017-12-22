<?php

class Base
{
	protected $config;
	protected $error_str;
	protected $Database;

	public function setConfig($config)
	{
		$this->config = $config;
	}

	public function setDatabase($Database)
	{
		$this->Database = $Database;
	}

	public function getDatabase()
	{
		return $this->Database;
	}

	protected function setErrorStr($error_str)
	{
		$this->error_str = $error_str;
	}
	
	public function getErrorStr()
	{
		return $this->error_str;
	}
}