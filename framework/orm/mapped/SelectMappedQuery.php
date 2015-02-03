<?php

	namespace framework\orm\query;
	/**
	 * Класс предназначен для выборки не отображенных данных с БД
	 * Т.е выборка на основании не связанных с моделью данных
	 *  
	 * @package orm.mapped
	 * @author Ruslan Molodyko
	 */
	class SelectMappedQuery extends SelectQuery{

		/**
		 * Добавление сущности через конструктор
		 * @param Mixed $entity (обьект данных)
		 */
		public function __construct(Model $entity){
			//Убрать имя пространства и получить имя класса модели
			$this->tableName = strtolower(preg_replace('/.*\\\/i','',get_class($entity)));
			return $this;
		}
	}