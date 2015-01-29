<?php
	require_once("/var/www/other/ORM/abstract/SelectQuery.php");

	/**
	 * Класс предназначен для выборки не отображенных данных с БД
	 * Т.е выборка на основании не связанных с моделью данных
	 * 
	 * @package orm.native
	 * @author Ruslan Molodyko
	 */
	class SelectNativeQuery extends SelectQuery{

		/**
		 * Добавление сущности через конструктор
		 * @param Mixed $dbName (имя таблицы)
		 */
		public function __construct($dbName){
			$this->tableName = strtolower($dbName);
			return $this;
		}
	}

	print_r((new SelectNativeQuery('movies'))->order('id')->limit(2,1)->execute());