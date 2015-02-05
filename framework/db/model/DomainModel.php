<?php

	namespace pyramid\db\model;

	/**
	 * Суперкласс для пользовательских моделей
	 * содержит основной функционал
	 */
	abstract class DomainModel{

		/**
		 * Хранит ссылку на DataMapper
		 * @var DataMapper
		 */
		public $_mapper = null;

		/**
		 * Получить ID модели
		 * @return Integer
		 */
		public function getId(){
			if(isset($this->id)){
				return $this->id;
			}
			return null;
		}

		/**
		 * Установить ID модели
		 * @param Integr $id 
		 */
		public function setId($id){
			$this->id = $id;
		}

		/**
		 * Получить DataMapper отвечает за роботу с БД
		 * @return DataMapper
		 */
		public function getMapper(){
			if($this->_mapper == null){
				$this->_mapper = DataMapper::getMapper($this);
			}
			return $this->_mapper;
		}

		/**
		 * Выполняет поиск строки в DB по ID
		 * Делегирование запроса объекту DataMapper
		 * @param  Integer $id
		 * @return Collection
		 */
		public function findById($id){
			$mapper = $this->getMapper();
			$res = $mapper->doFindById($id);
			return $res;
		}

		/**
		 * Выполняет поиск всех строк в DB
		 * Делегирование запроса объекту DataMapper
		 * @param  Array $order Параметры запроса для сортировки запроса
		 * @param  Array $limit Параметры запроса для вывода количества данных в запросе
		 * @return Collection
		 */
		public function findAll(Array $order = null,Array $limit = null){
			$mapper = $this->getMapper();
			$res = $mapper->doFindAll($order,$limit);
			return $res;
		}

		/**
		 * Выполняет поиск всех строк в DB по условию
		 * Делегирование запроса объекту DataMapper
		 * @param  Array $where Параметры запроса
		 * @param  Array $order Параметры запроса для сортировки запроса
		 * @param  Array $limit Параметры запроса для вывода количества данных в запросе
		 * @return Collection
		 */
		public function findAllByAttributes(Array $where = null,Array $order = null,Array $limit = null){
			$mapper = $this->getMapper();
			$res = $mapper->doFindAllByAttribures($where,$order,$limit);
			return $res;
		}

		/**
		 * Выполняет поиск первой подходящей под условие строки в DB по условию
		 * Делегирование запроса объекту DataMapper
		 * @param  Array $where Параметры запроса
		 * @param  Array $order Параметры запроса для сортировки запроса
		 * @param  Array $limit Параметры запроса для вывода количества данных в запросе
		 * @return Collection
		 */
		public function findByAttributes(Array $where = null,Array $order = null,Array $limit = null){
			$mapper = $this->getMapper();
			$res = $mapper->doFindByAttribures($where,$order,$limit);
			return $res;
		}

		/**
		 * Выполняет обновление или вставку объекта в DB
		 * в зависимости от того существует ли ID в объекта
		 * и в зависимости от того существует ли такой ID в базе
		 * @return Boolean
		 */
		public function save(){
			$mapper = $this->getMapper();
			if(!is_null($this->getId)){
				//Делаем запрос в БД для проверки есть такая строка или нет
				if(!$mapper->doFindByAttribures(['id'=>$this->getId])->isEmpty()){
					return $res = $mapper->doUpdate();
				}
			}
			return $res = $mapper->doInsert();
		}
	}