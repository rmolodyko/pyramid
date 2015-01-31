<?php

	/**
	 * Класс предназначен для обновления данных в БД, отображенных данных
	 * 
	 * @package orm.mapped
	 * @author Ruslan Molodyko
	 */
	class UpdateMappedQuery extends UpdateQuery{
		/**
		 * Инициализируем объект запроса, объектом модели
		 * @param InsertMappedQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function __construct(Model $entity){
			$this->tableName = strtolower(get_class($entity));
			parent::set($this->getNativePropertiesModel($entity));
			return $this;
		}

		/**
		 * Запрещаем вызывать данный метод, так как он не может существовать в mapped типе
		 */
		public function set(Array $foo){
			throw new Exception("Attempt to call not callable method");
		}

	}