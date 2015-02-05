<?php
	namespace pyramid\db\model;

	/**
	 * Класс коллекция для пользовательских моделей
	 */
	class Collection implements \Iterator{

		/**
		 * Счетчик для итератора
		 * @var Integer
		 */
		protected $pointer = 0;

		/**
		 * Массив с сырыми данными на основании каких будут создаваться экземпляры моделей
		 * @var Array
		 */
		protected $raw = [];

		/**
		 * Счетчик количества элементов в массиве $raw
		 *
		 * @var Integer
		 */
		protected $count = 0;

		/**
		 * Хранит уже созданные экземпляры моделей
		 * @var Array
		 */
		protected $object = [];

		/**
		 * Тип коллекции, имя класса пользовательской модели
		 * @var [type]
		 */
		protected $domainName;

		/**
		 * Создание коллекции через new
		 * @param String     $nameClass Имя класса пользовательской модели
		 * @param Array|null $raw       Сырые данные полученные из базы
		 */
		function __construct($nameClass,Array $raw = null){
			if(!is_null($raw)){
				$this->raw = $raw;
				$this->count = count($raw);
			}
			if(is_null($nameClass)){
					throw new \Exception("Неопределенное имя коллекции");
			}
			$this->domainName = $nameClass;
		}

		/**
		 * Получить тип коллекции
		 * @return String
		 */
		function getCollectionName(){
			return $this->domainName;
		}

		/**
		 * Добавить новый объект в коллекцию
		 * @param DomainModel $obj
		 */
		function add(DomainModel $obj){
			$domainName = $this->getCollectionName();
			if( ! ($obj instanceof $domainName ) ){
				throw new \Exception("Неверный тип переданного объекта в коллекцию");
			}
			$this->object[$count] = $obj;
			$this->count++;
		}

		/**
		 * Получить значение отдельного элемента
		 * @param  Integer $n Номер элемента
		 * @return DomainModel
		 */
		function getRow($n){
			$domainName = $this->getCollectionName();
			if(($n < 0) || ($n >= $this->count)){
				return null;
			}

			if(isset($this->object[$n])){
				return $this->object[$n];
			}
			
			if(isset($this->raw[$n])){
				$domain = DomainObjectFactory::createObject($domainName,$this->raw[$n]);
				$this->object[$n] = $domain;
				return $domain;
			}
		}

		/**
		 * Проверяет пуста ли коллекция
		 * @return Boolean
		 */
		function isEmpty(){
			if(isset($this->raw)&&!empty($this->raw)){
				return false;
			}
			if(isset($this->raw)&&!empty($this->object)){
				return false;
			}
			return true;
		}

		/**
		 * Реализация итератора
		 */
		function rewind(){
			$this->pointer = 0;
		}

		function current(){
			return $this->getRow($this->pointer);
		}

		function key(){
			return $this->pointer;
		}

		function next(){
			$row = $this->getRow($this->pointer);
			if($row){
				$this->pointer++;
			}
			return $row;
		}

		function valid(){
			return (!is_null($this->current()));
		}
	}