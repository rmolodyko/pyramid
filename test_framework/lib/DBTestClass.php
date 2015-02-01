<?php

	/**
	 * Класс от которого наследуются все тестовые классы, содержит необходимый функционал
	 */
	class DBTestClass extends PHPUnit_Extensions_Database_TestCase{

		protected $pdo = null;

		protected function pathToDataSet(){
			return dirname(__FILE__)."/../fixtures/dataset.xml";
		}

		public function getConnection()
		{
			$this->pdo = new PDO('mysql:host=localhost;dbname=movie_db_test', 'root', 'muha1990');
			return $this->createDefaultDBConnection($this->pdo, 'movie_db_test');
		}

		public function getDataSet()
		{
			return $this->createFlatXMLDataSet($this->pathToDataSet());
		}

		/**
		 * Делает и выводит результат запроса в БД
		 * @param  String $sql sql запрос
		 * @return Array
		 */
		public function execQuery($sql){
			$STH =  $this->pdo->prepare($sql);
			$STH->execute();
			$mw = [];
			$STH->setFetchMode(PDO::FETCH_ASSOC);
			while($obj = $STH->fetch()){
				$mw[] = $obj;
			}
			return $mw;
		}

		/**
		 * Хранит номера блоков try, в которых произошла предполагаемая ошибка
		 * @var Array
		 */
		protected $issetExpectedError = [];

		/**
		 * Хранит номера блоков try, в которых не произошла ошибка и которая не ожидалась
		 * @var Array
		 */
		protected $issetError = [];

		/**
		 * Метод вызывается в самом начале блока try, ожидая исключение
		 * @param  Integer $n Номер try блока
		 */
		protected function expectError($n){
			$this->issetExpectedError[$n] = false;
		}

		/**
		 * Метод вызывается в самом начале блока try, в котором НЕ ожидается исключение
		 * @param  Integer $n Номер try блока
		 */
		protected function notExpectError($n){
			$this->issetError[$n] = false;
		}

		/**
		 * Метод вызывается в блоке catch, показывая на то что как и ожидалось исключение брошено
		 * @param  Integer $n Номер try блока
		 */
		protected function issuedError($n){
			$this->issetExpectedError[$n] = true;
			if(isset($this->issetError[$n]))
			$this->issetError[$n] = true;
		}

		/**
		 * Если есть блок try который не выдал исключение то тест завершается неудачей со списком всех неверных блоков try
		 */
		protected function throwErrors(){
			$arrKey1 = [];
			if(in_array(false,$this->issetExpectedError)){
				foreach($this->issetExpectedError as $key => $value){
					if($value === false) $arrKey1[] = $key;
				}
			}
			$arrKey2 = [];
			if(in_array(true,$this->issetError)){
				foreach($this->issetError as $key => $value){
					if($value === true) $arrKey2[] = $key;
				}
			}
			$str = '';
			if(!empty($arrKey1)){
				$str = "Не произошло ожидаемого исключения в блоках try - {".implode(",",$arrKey1)."}\n";
			}
			if(!empty($arrKey2)){
				$str .= "Произошла непредвиденная ошибка в блоках try - {".implode(",",$arrKey2)."}\n";
			}
			if($str !== '')
				$this->fail($str);
		}
}