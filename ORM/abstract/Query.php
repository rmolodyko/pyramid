<?php

	require_once("/var/www/other/ORM/helper/DBHelper.php");

	abstract class Query{

		protected $tableName;

		protected function getDBHandler(){
			return DBHelper::getDBHandler();
		}

		abstract public function execute();

	}