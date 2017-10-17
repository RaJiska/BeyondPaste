<?php

class Database extends Base
{
	private $echo_errors;

	public function connect($echo_errors = false)
	{
		$this->echo_errors = $echo_errors;

		try
		{
			$this->sqlres = new PDO(
				'mysql:host=' . $this->config['db']['host'] . ';dbname=' . $this->config['db']['name'],
				$this->config['db']['username'], $this->config['db']['password'],
				array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_PERSISTENT => true
				)
			);
		}
		catch (PDOException $e)
		{
			$this->logError($e);
			$this->sqlres = null;
		}
	}

	public function isConnected()
	{
		return ($this->sqlres != null);
	}

	public function setEchoErrors($echo_errors)
	{
		$this->echo_errors = $echo_errors;
	}

	public function getEchoErrors($echo_errors)
	{
		return $this->echo_errors;
	}

	private function logError($exception)
	{
		if ($this->echo_errors)
			echo('Database error: ' . htmlspecialchars($exception->getMessage()));
	}
}

$Database = new Database();
$Database->setConfig($config);
$Database->connect();