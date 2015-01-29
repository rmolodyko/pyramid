<?php
	require_once("/var/www/other/ORM/abstract/SelectQuery.php");

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
			$this->tableName = strtolower(get_class($entity));
			return $this;
		}
	}

	class Model{}

	class Movies extends Model{

	}

	print_r((new SelectMappedQuery(new Movies))->order('id')->limit(2,1)->execute());