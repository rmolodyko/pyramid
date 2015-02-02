<?php

	/**
	 * Класс предназначен для удаления данных из БД, отображенный данных
	 *  
	 * @package orm.mapped
	 * @author Ruslan Molodyko
	 */
	class DeleteMappedQuery extends DeleteQuery{

		/**
		 * Инициализируем объект запроса, объектом модели
		 * @param InsertMappedQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function __construct(Model $entity){
			$this->tableName = strtolower(get_class($entity));
			return $this;
		}
	}