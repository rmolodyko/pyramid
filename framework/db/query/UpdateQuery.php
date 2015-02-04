<?php

	namespace pyramid\db\query;
	/**
	 * Класс предназначен для обновления данных в БД
	 */
	class UpdateQuery extends Query{

		/**
		 * Параметры которые нужно обновить в БД
		 * @var Array
		 */
		protected $values;

		/**
		 * Установка значений обновляемых параметров
		 * @param  Array  $values
		 * @return InsertQuery    Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function set(Array $values){
			if(!isset($values)||empty($values)){
				throw new \Exception("Отсутствуют значения параметров для вставки");
			}
			$this->values = $values;
			return $this;
		}

		/**
		 * Возвратить строку для части SET sql запроса
		 * @return String
		 */
		protected function isSetThenGetStrQuery(){
			if(isset($this->values)&&!empty($this->values)){
				$queryString = '';
				$newArray = [];
				foreach($this->values as $key => $value){
					if($value === null) continue; //Реакция на @todo
					$newArray[] = "$key = :{$key}_unique";
				}
				$queryString = ' '.implode(' , ',$newArray).' ';
				return $queryString;
			}else{
				throw new \Exception("Отсутствуют значения параметров для вставки");
			}
		}

		/**
		 * Возвратить массив с параметрами SET для PDO::execute()
		 * @return Array
		 */
		protected function isSetThenGetValueQuery(){
			if(isset($this->values)&&!empty($this->values)){
				$newArray = [];
				foreach($this->values as $key => $value){
					if($value === null) continue; //Реакция на @todo
					$newArray["{$key}_unique"] = $value;
				}
				return $newArray;
			}else{
				throw new \Exception("Отсутствуют значения параметров для вставки");
			}
		}

		/**
		 * Метод строит на основании имеющегося запроса, строку SQL с запросом к БД
		 * @return String Строка с запросом
		 */
		protected function buildQueryString(){
			$queryString  = ' UPDATE ';
			$queryString .= $this->isTableNameThenGetStrQuery();
			$queryString .= ' SET ';
			$queryString .= $this->isSetThenGetStrQuery();
			$queryString .= $this->isWhereThenGetStrQuery();
			return $queryString;
		}

		/**
		 * Метод который делает обновление в БД
		 * @return Integer Колличество обработанных записей
		 */
		public function execute(){
			$STH = $this->getDBHandler()->prepare($this->buildQueryString());
			$STH->execute(array_merge($this->isWhereThenGetValueQuery(),$this->isSetThenGetValueQuery()));
			return $STH->rowCount();
		}
	}