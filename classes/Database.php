<?php

class Database
{
	public $res;
	public $echo_errors;

	public function __construct($config, $echo_errors = false)
	{
		$this->echo_errors = $echo_errors;

		try
		{
			$this->res = new PDO(
				'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'],
				$config['db']['username'], $config['db']['password'],
				array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_PERSISTENT => true
				)
			);
		}
		catch (PDOException $e)
		{
			$this->log_error($e);
			$this->res = null;
		}
	}

	private function log_error($exception)
	{
		if ($this->echo_errors)
			echo('Database error: ' . htmlspecialchars($exception->getMessage()));
	}
}