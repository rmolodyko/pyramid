<?php

	namespace pyramid\db\query\mapped;
	use \pyramid\db\query\UpdateQuery;
	use \pyramid\db\model\Model;
	/**
	 * Класс предназначен для обновления данных в БД, отображенных данных
	 *  
	 * @todo Решить должен ли класс обрабатывать не инициализированные поля(по умолчанию равны null) экземпляра
	 * если да, то изменить поведение тестов
	 */
	class UpdateMappedQuery extends UpdateQuery{
		/**
		 * Инициализируем объект запроса, объектом модели
		 * @param InsertMappedQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function __construct(Model $entity){
			//Убрать имя пространства и получить имя класса модели
			$this->tableName = strtolower(preg_replace('/.*\\\/i','',get_class($entity)));
			parent::set($this->getNativePropertiesModel($entity));
			return $this;
		}

		/**
		 * Запрещаем вызывать данный метод, так как он не может существовать в mapped типе
		 */
		public function set(Array $foo){
			throw new \Exception("Attempt to call not callable method");
		}

	}