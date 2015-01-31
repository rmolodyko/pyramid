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
		 * Хранит номера блоков try
		 * @var Array
		 */
		protected $errNum = [];

		/**
		 * Метод вызывается в самом начале блока try, ожидая исключение
		 * @param  Integer $n Номер try блока
		 */
		protected function expectError($n){
			$this->errNum[$n] = true;
		}

		/**
		 * Метод вызывается в блоке catch, показывая на то что как и ожидалось исключение брошено
		 * @param  Integer $n Номер try блока
		 */
		protected function issuedError($n){
			$this->errNum[$n] = false;
		}

		/**
		 * Если есть блок try который не выдал исключение то тест завершается неудачей со списком всех неверных блоков try
		 */
		protected function throwErrors(){
			if(in_array(true,$this->errNum)){
				$arrKey = [];
				foreach($this->errNum as $key => $value){
					if($value) $arrKey[] = $key;
				}
				$this->fail("Не произошло ожидаемого исключения в блоках try - {".implode(",",$arrKey)."}");
			}
		}
}