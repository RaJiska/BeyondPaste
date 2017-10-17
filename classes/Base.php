<?php

class Base
{
	protected $config;
	protected $sqlres;
	protected $error_str;

	public function setConfig($config)
	{
		$this->config = $config;
	}

	public function getConfig($config)
	{
		return $this->config;
	}

	public function setSqlres($sqlres)
	{
		$this->sqlres = $sqlres;
	}

	public function getSqlres()
	{
		return $this->sqlres;
	}

	protected function setErrorStr($error_str)
	{
		$this->error_str = $error_str;
	}
	
	public function getErrorStr($error_str)
	{
		return $error_str;
	}
}