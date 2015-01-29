<?php

	require_once("/var/www/other/ORM/helper/DBHelper.php");

	abstract class Query{

		protected $tableName;

		protected function getDBHandler(){
			return DBHelper::getDBHandler();
		}

		abstract public function execute();

		/**
		 * Если заданно имя таблицы, то преобразовать ее в строку SQL
		 * Иначе вызвать исключение
		 * @return String
		 */
		protected function isTableNameThenGetStrQuery(){
			if(isset($this->tableName)&&!empty($this->tableName)){
				return " {$this->tableName} ";
			}else{
				throw new Exception("Не указано имя таблицы");
			}
		}

	}