<?php
	require_once("/var/www/other/ORM/abstract/Query.php");
	class InsertQuery extends Query{

		protected $values;

		public function __construct($dbName){
			$this->tableName = strtolower($dbName);
			return $this;
		}

		public function values(Array $values){
			if(!isset($values)||empty($values)){
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
			$this->values = $values;
			return $this;
		}

		protected function isValuesThenGetStrQueryKeys(){
			if(isset($this->values)&&!empty($this->values)){
				$queryString = ' ( '.implode(' , ',array_flip($this->values)).' ) ';
				return $queryString;
			}else{
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
		}

		protected function isValuesThenGetStrQueryValues(){
			if(isset($this->values)&&!empty($this->values)){
				$queryString = '( :'.implode(' , :',array_flip($this->values)).' ) ';
				return $queryString;
			}else{
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
		}

		protected function isValuesThenGetValueQuery(){
			if(isset($this->values)&&!empty($this->values)){
				return $this->values;
			}else{
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
		}

		protected function buildQueryString(){
			$queryString  = ' INSERT INTO ';
			$queryString .= $this->isTableNameThenGetStrQuery();
			$queryString .= $this->isValuesThenGetStrQueryKeys();
			$queryString .= ' VALUES ';
			$queryString .= $this->isValuesThenGetStrQueryValues();

			return $queryString;
		}

		public function execute(){
			$STH =  $this->getDBHandler()->prepare($this->buildQueryString());
			if($STH->execute($this->isValuesThenGetValueQuery())){
				$STH = $this->getDBHandler()->query("SELECT max(id) FROM {$this->isTableNameThenGetStrQuery()}");
				$obj = $STH->fetch();
				if(isset($obj[0])&&((int)$obj[0])){
					return $obj[0];
				}else{
					throw new Exception("Произошла ошибка при получении ID последней вставки");
				}
			}else{
				throw new Exception("Произошла ошибка при выполнении запроса");
			}
		}
	}

	print_r((new InsertQuery('movies'))->values(['title'=>'My life','year'=>9999,'format'=>'DVD'])->execute());