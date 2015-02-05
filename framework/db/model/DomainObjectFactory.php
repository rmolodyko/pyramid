<?php

	namespace pyramid\db\model;

	/**
	 * Клас принимает полномочия по созданию экземпляра пользовательской модели
	 */
	abstract class DomainObjectFactory{

		/**
		 * Создать пользовательскую модель
		 * @param  String $domainName   Имя пользовательской модели
		 * @param  Array  $fieldsDomain Массив значений полей модели
		 * @return DomainModel
		 */
		public static function createObject($domainName,Array $fieldsDomain){
			$obj = new $domainName();
			$obj->init($fieldsDomain);
			return $obj;
		}
	}