<?php

	/**
	 * Класс предназначен для вставки данных в БД
	 * 
	 * @package orm.abstract
	 * @author Ruslan Molodyko
	 */
	class InsertQuery extends Query{

		/**
		 * Параметры которые нужно сохранить в БД
		 * @var Array
		 */
		protected $values;

		/**
		 * Установка значений вставляемых параметров
		 * @param  Array  $values
		 * @return InsertQuery    Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function values(Array $values){
			if(!isset($values)||empty($values)){
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
			$this->values = $values;
			return $this;
		}

		/**
		 * Получаем строку перечисления имен параметров в виде SQL
		 * @return String
		 */
		protected function isValuesThenGetStrQueryKeys(){
			if(isset($this->values)&&!empty($this->values)){
				$queryString = ' ( '.implode(' , ',array_flip($this->values)).' ) ';
				return $queryString;
			}else{
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
		}

		/**
		 * Получаем строку перечисления имен параметров для PDO, в виде SQL
		 * @return String
		 */
		protected function isValuesThenGetStrQueryValues(){
			if(isset($this->values)&&!empty($this->values)){
				$queryString = '( :'.implode(' , :',array_flip($this->values)).' ) ';
				return $queryString;
			}else{
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
		}

		/**
		 * Получаем параметры для PDO::execute()
		 * @return Array
		 */
		protected function isValuesThenGetValueQuery(){
			if(isset($this->values)&&!empty($this->values)){
				return $this->values;
			}else{
				throw new Exception("Отсутствуют значения параметров для вставки");
			}
		}

		/**
		 * Метод строит на основании имеющегося запроса, строку SQL с запросом к БД
		 * @return String Строка с запросом
		 */
		protected function buildQueryString(){
			$queryString  = ' INSERT INTO ';
			$queryString .= $this->isTableNameThenGetStrQuery();
			$queryString .= $this->isValuesThenGetStrQueryKeys();
			$queryString .= ' VALUES ';
			$queryString .= $this->isValuesThenGetStrQueryValues();

			return $queryString;
		}

		/**
		 * Метод который делает вставку в БД
		 * @return Integer ID вставленной записи
		 */
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