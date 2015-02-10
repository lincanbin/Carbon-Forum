<?php
/*
 * PHP-PDO-MySQL-Class
 * https://github.com/lincanbin/PHP-PDO-MySQL-Class
 *
 * Copyright 2014, Lin Canbin
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A PHP MySQL PDO class similar to the the Python MySQLdb. 
 */
require(dirname(__FILE__) . "/PDO.Log.class.php");
class DB
{
	private $Host;
	private $DBName;
	private $DBUser;
	private $DBPassword;
	private $pdo;
	private $sQuery;
	private $bConnected = false;
	private $log;
	private $parameters;
	public $querycount = 0;
	
	
	public function __construct($Host, $DBName, $DBUser, $DBPassword)
	{
		$this->log        = new Log();
		$this->Host       = $Host;
		$this->DBName     = $DBName;
		$this->DBUser     = $DBUser;
		$this->DBPassword = $DBPassword;
		$this->Connect();
		$this->parameters = array();
	}
	
	
	private function Connect()
	{
		try {
			$this->pdo = new PDO('mysql:dbname=' . $this->DBName . ';host=' . $this->Host, $this->DBUser, $this->DBPassword, array(
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				//PDO::ATTR_PERSISTENT => true,//长连接
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
				
				
			));
			$this->bConnected = true;
		}
		catch (PDOException $e) {
			echo $this->ExceptionLog($e->getMessage());
			die();
		}
	}
	
	
	public function CloseConnection()
	{
		$this->pdo = null;
	}
	
	
	private function Init($query, $parameters = "")
	{
		if (!$this->bConnected) {
			$this->Connect();
		}
		try {
			$this->parameters = $parameters;
			$this->sQuery     = $this->pdo->prepare($this->BuildParams($query, $this->parameters));
			
			if (!empty($this->parameters)) {
				if (array_key_exists(0, $parameters)) {
					$parametersType = true;
					array_unshift($this->parameters, "");
					unset($this->parameters[0]);
				} else {
					$parametersType = false;
				}
				foreach ($this->parameters as $column => $value) {
					$this->sQuery->bindParam($parametersType ? intval($column) : ":" . $column, $this->parameters[$column]); //It would be query after loop end(before 'sQuery->execute()').It is wrong to use $value.
				}
			}
			
			$this->succes = $this->sQuery->execute();
			$this->querycount++;
		}
		catch (PDOException $e) {
			echo $this->ExceptionLog($e->getMessage(), $this->BuildParams($query));
			die();
		}
		
		$this->parameters = array();
	}
	
	private function BuildParams($query, $params = null)
	{
		if (!empty($params)) {
			$rawStatement = explode(" ", $query);
			foreach ($rawStatement as $value) {
				if (strtolower($value) == 'in') {
					return str_replace("(?)", "(" . implode(",", array_fill(0, count($params), "?")) . ")", $query);
				}
			}
		}
		return $query;
	}
	
	
	public function query($query, $params = null, $fetchmode = PDO::FETCH_ASSOC)
	{
		$query        = trim($query);
		$rawStatement = explode(" ", $query);
		$this->Init($query, $params);
		$statement = strtolower($rawStatement[0]);
		if ($statement === 'select' || $statement === 'show') {
			return $this->sQuery->fetchAll($fetchmode);
		} elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
			return $this->sQuery->rowCount();
		} else {
			return NULL;
		}
	}
	
	
	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
	
	
	public function column($query, $params = null)
	{
		$this->Init($query, $params);
		return $this->sQuery->fetchAll(PDO::FETCH_COLUMN);
		
	}
	
	
	public function row($query, $params = null, $fetchmode = PDO::FETCH_ASSOC)
	{
		$this->Init($query, $params);
		$resuleRow = $this->sQuery->fetch($fetchmode);
		$this->sQuery->closeCursor();
		return $resuleRow;
	}
	
	
	public function single($query, $params = null)
	{
		$this->Init($query, $params);
		return $this->sQuery->fetchColumn();
	}
	
	
	private function ExceptionLog($message, $sql = "")
	{
		$exception = 'Unhandled Exception. <br />';
		$exception .= $message;
		$exception .= "<br /> You can find the error back in the log.";
		
		if (!empty($sql)) {
			$message .= "\r\nRaw SQL : " . $sql;
		}
		$this->log->write($message);
		//Prevent search engines to crawl
		header("HTTP/1.1 500 Internal Server Error");
		header("Status: 500 Internal Server Error");
		return $exception;
	}
}