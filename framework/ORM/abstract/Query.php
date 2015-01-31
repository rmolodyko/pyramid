<?php

	/**
	 * Тип данных, и функционал для запросов к БД
	 * 
	 * @package orm.abstract
	 * @author Ruslan Molodyko
	 */
	abstract class Query{

		/**
		 * Имя основной таблицы с какой работает запрос
		 * @var String
		 */
		protected $tableName;

		/**
		 * Хранит данные запроса с WHERE если он задан
		 * @var Array ['key'=>'value',...]
		 */
		protected $whereAttributes;

		/**
		 * Абстрактный метод, который непосредственно выполняет запрос и возвращает результат
		 * @return Mixed Вывод зависит от того объектный ли это запрос или сырой
		 */
		abstract public function execute();

		/**
		 * Возвращает объект PDO
		 * @return PDO
		 */
		protected function getDBHandler(){
			return DBHelper::getDBHandler();
		}

		/**
		 * Инициализируем объект запроса, строкой имени таблицы БД
		 * 
		 * ПО УМОЛЧАНИЮ ПРОИСХОДИТ ИНИЦИАЛИЗАЦИЯ NATIVE ЗАПРОСА
		 * MAPPED конструкторы нужно переопределять
		 * 
		 * @param InsertNativeQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function __construct($dbName){
			$this->tableName = strtolower($dbName);
			return $this;
		}

		/**
		 * Метод устанавливает параметры для запроса с WHERE
		 * @param  Array|null $attributes Массив параметров ['key'=>'value',...]
		 * @return SelectQuery      Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function where(Array $attributes = null){
			$this->whereAttributes = $attributes;
			return $this;
		}

		/**
		 * Если заданно имя таблицы, то преобразовать ее в строку SQL
		 * Иначе вызвать исключение
		 * @return String
		 */
		protected function isTableNameThenGetStrQuery(){
			if(isset($this->tableName)&&!empty($this->tableName)){
				return " {$this->tableName} ";
			}else{
				throw new Exception("Не указано имя таблицы");
			}
		}

		/**
		 * Если заданные данные WHERE, то преобразовать их массив для PDO::execute()
		 * @return Array|null
		 */
		protected function isWhereThenGetValueQuery(){
			if(isset($this->whereAttributes)&&!empty($this->whereAttributes)){
				$valueQuery = [];
				foreach($this->whereAttributes as $k => $v){
					$valueQuery[str_replace(".","_",$k)] = $v;
				}
				return $valueQuery;
			}else{
				return null;
			}
		}

		/**
		 * Если заданные данные WHERE, то преобразовать их в строку SQL
		 * @return String
		 */
		protected function isWhereThenGetStrQuery(){
			if(isset($this->whereAttributes)&&!empty($this->whereAttributes)){
				$queryString = '';
				foreach($this->whereAttributes as $k => $v){
					$queryString .= "$k = :".str_replace(".","_",$k);
				}
				return " WHERE $queryString ";
			}else{
				return '';
			}
		}

		/**
		 * Получить родимые(объявлены в непосредственном классе модели) свойства
		 * @param  Modle $entity Объект модели
		 * @return Array         Возвращает массив со значениями модели
		 */
		protected function getNativePropertiesModel($entity){

			$values = [];
			$ref = new ReflectionClass(get_class($entity));
			foreach($ref->getProperties() as $v){
				if($v->getDeclaringClass()->getName() !== get_class($entity)) continue;
				$key_name = $v->getName();
				$values[$key_name] = $entity->$key_name;
			}
			/*
			print "----Array----\n";
			print_r($values);
			print "----EndArray----\n";
			*/
			return $values;
		}

	}