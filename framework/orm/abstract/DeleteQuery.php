<?php

	namespace framework\orm\query;
	/**
	 * Класс предназначен для удаления данных из БД
	 * 
	 * @package orm.abstract
	 * @author Ruslan Molodyko
	 */
	class DeleteQuery extends Query{

		/**
		 * Метод строит на основании имеющегося запроса, строку SQL с запросом к БД
		 * @return String Строка с запросом
		 */
		protected function buildQueryString(){
			$queryString  = ' DELETE FROM ';
			$queryString .= $this->isTableNameThenGetStrQuery();
			$queryString .= $this->isWhereThenGetStrQuery();
			return $queryString;
		}

		/**
		 * Метод который делает удаление из БД
		 * @return Integer Колличество обработанных записей
		 */
		public function execute(){
			$STH =  $this->getDBHandler()->prepare($this->buildQueryString());
			$STH->execute($this->isWhereThenGetValueQuery());
			return $STH->rowCount();
		}
	}