<?php

	namespace pyramid\db\model;
	use \pyramid\db\query\mapped\SelectMappedQuery;
	use \pyramid\db\query\mapped\UpdateMappedQuery;
	use \pyramid\db\query\mapped\InsertMappedQuery;

	/**
	 * Класс отвечает за синхронизацию пользовательской модели с БД
	 */
	class DataMapper{

		/**
		 * Хранит экземпляр пользовательской модели
		 * @var DomainModel
		 */
		protected $currentDomainObject = null;

		/**
		 * Хранит имя класса пользовательской модели
		 * @var String
		 */
		protected $currentDomainClassName = null;

		/**
		 * Хранит экземпляры объектов DataMapper
		 * Реализация для Singleton
		 * @todo Пока что сделал Singleton так как есть подозрение что он понадобится для Identity Map
		 * @var Array
		 */
		static protected $instance = [];

		/**
		 * Закрытый конструктор что бы нельзя было создать экземпляр через new
		 * @param DomainModel $domainObj
		 */
		private function __construct($domainObj){
			$this->currentDomainObject = $domainObj;
		}

		/**
		 * Возвращает из кеша или создает новый объект DataMapper определенного типа
		 * @param  DomainModel $domainObj Пользовательская модель
		 * @return DataMapper
		 */
		public function getMapper(DomainModel $domainObj){
			$domainClassName = get_class($domainObj);
			if(isset(self::$instance[$domainClassName])){
				return self::$instance[$domainClassName];
			}
			self::$instance[$domainClassName] = new DataMapper($domainObj);
			self::$instance[$domainClassName]->currentDomainClassName = $domainClassName;

			return self::$instance[$domainClassName];
		}

		/**
		 * Получить имя класса пользовательской модели
		 * @return String
		 */
		public function getTypeNameMapper(){
			return $this->currentDomainClassName;
		}

		/**
		 * Получить текущий экземпляр пользовательской модели
		 * @return DomainModel
		 */
		public function getDomainObject(){
			return $this->currentDomainObject;
		}

		/**
		 * Выполняет поиск строки в DB по ID
		 * @param  Integer $id
		 * @return Collection
		 */
		public function doFindById($id){
			$queryObj = new SelectMappedQuery($this->getDomainObject());
			$res = $queryObj->where(['id'=>$id])->execute();
			$collection = new Collection($this->getTypeNameMapper(),$res);
			return $collection;
		}

		/**
		 * Выполняет поиск всех строк в DB
		 * @param  Array $order Параметры запроса для сортировки запроса
		 * @param  Array $limit Параметры запроса для вывода количества данных в запросе
		 * @return Collection
		 */
		public function doFindAll(Array $order = null,Array $limit = null){
			$queryObj = new SelectMappedQuery($this->getDomainObject());
			if(!is_null($order)){
				$queryObj->order($order[0],isset($order[1])?$order[1]:false);
			}
			if(!is_null($limit)){
				$queryObj->limit($limit[0],isset($limit[1])?$limit[1]:null);
			}
			$res = $queryObj->execute();
			$collection = new Collection($this->getTypeNameMapper(),$res);
			return $collection;
		}

		/**
		 * Выполняет поиск всех строк в DB по условию
		 * @param  Array $where Параметры запроса
		 * @param  Array $order Параметры запроса для сортировки запроса
		 * @param  Array $limit Параметры запроса для вывода количества данных в запросе
		 * @return Collection
		 */
		public function doFindAllByAttribures(Array $where = null,Array $order = null,Array $limit = null){
			$queryObj = new SelectMappedQuery($this->getDomainObject());
			if(!is_null($where)){
				$queryObj->where($where);
			}
			if(!is_null($order)){
				$queryObj->order($order[0],isset($order[1])?$order[1]:false);
			}
			if(!is_null($limit)){
				$queryObj->limit($limit[0],isset($limit[1])?$limit[1]:null);
			}
			$res = $queryObj->execute();
			$collection = new Collection($this->getTypeNameMapper(),$res);
			return $collection;
		}

		/**
		 * Выполняет поиск первой подходящей под условие строки в DB по условию
		 * @param  Array $where Параметры запроса
		 * @param  Array $order Параметры запроса для сортировки запроса
		 * @param  Array $limit Параметры запроса для вывода количества данных в запросе
		 * @return Collection
		 */
		public function doFindByAttribures(Array $where = null,Array $order = null){
			$queryObj = new SelectMappedQuery($this->getDomainObject());
			$queryObj->single(true);
			if(!is_null($where)){
				$queryObj->where($where);
			}
			if(!is_null($order)){
				$queryObj->order($order[0],isset($order[1])?$order[1]:false);
			}
			$res = $queryObj->execute();
			$collection = new Collection($this->getTypeNameMapper(),$res);
			return $collection;
		}

		/**
		 * Реализация функционала обновления записи
		 * @return Boolean
		 */
		public function doUpdate(){
			$queryObj = new UpdateMappedQuery($this->getDomainObject());
			$res = $queryObj->where(['id'=>$this->getDomainObject()->getId()])->execute();
			return $res;
		}

		/**
		 * Реализация функционала вставки записи
		 * @return Boolean
		 */
		public function doInsert(){
			$queryObj = new InsertMappedQuery($this->getDomainObject());
			$res = $queryObj->execute();
			return $res;
		}
	}