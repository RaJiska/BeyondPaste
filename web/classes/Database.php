<?php

class Database extends PDO
{
	private $echo_errors;
	private $config;

	public function __construct($config, $echo_errors = false)
	{
		$this->echo_errors = $echo_errors;
		$this->config = $config;

		try
		{
			parent::__construct(
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
			throw $e;
		}
	}

	public function executeTransaction($queries, $binds, $ret_lastid = false)
	{
		$return = true;

		try
		{
			$this->beginTransaction();

			foreach($queries as $key => $query)
			{
				$stmt = $this->prepare($queries[$key]);
				$stmt->execute($binds[$key]);
			}

			if ($ret_lastid)
				$return = $this->lastInsertId();
			$this->commit();
		}
		catch (PDOException $e)
		{
			$this->rollBack();
			throw $e;
		}

		return $return;
	}

	/* Setters / Getters */

	public function setEchoErrors($echo_errors)
	{
		$this->echo_errors = $echo_errors;
	}

	public function getEchoErrors()
	{
		return $this->echo_errors;
	}
}

$Database = null;

try
{
	$Database = new Database($config);
}
catch (PDOException $e)
{
	error_log("Could not connect to the database: " . $e->getMessage());
	die("Could not connect to the database");
}