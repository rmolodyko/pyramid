<?php

	namespace pyramid\db\query\mapped;
	use \pyramid\db\query\DeleteQuery;
	use \pyramid\db\model\Model;
	/**
	 * Класс предназначен для удаления данных из БД, отображенный данных
	 */
	class DeleteMappedQuery extends DeleteQuery{

		/**
		 * Инициализируем объект запроса, объектом модели
		 * @param InsertMappedQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function __construct(Model $entity){
			//Убрать имя пространства и получить имя класса модели
			$this->tableName = strtolower(preg_replace('/.*\\\/i','',get_class($entity)));
			return $this;
		}
	}