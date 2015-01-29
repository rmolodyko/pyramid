<?php
	require_once("/var/www/other/ORM/abstract/Query.php");

	/**
	 * Класс предназначен для выборки данных с БД
	 * 
	 * @package orm.abstract
	 * @author Ruslan Molodyko
	 */
	abstract class SelectQuery extends Query{

		/**
		 * Хранит данные запроса с WHERE если он задан
		 * @var Array ['key'=>'value',...]
		 */
		protected $whereAttributes;

		/**
		 * Хранит данные: какие имена столбцов нужно получить
		 * @var Array ['key1',...]
		 */
		protected $keys;

		/**
		 * По какому столбцу сортировать результат запроса
		 * @var Array
		 */
		protected $orderColumn;

		/**
		 * Данные какие данные выбирать (их номер)
		 * @var Array
		 */
		protected $limit;

		/**
		 * Метод устанавливает параметры для запроса с WHERE
		 * @param  Array|null $attributes Массив параметров ['key'=>'value',...]
		 * @return SelectNativeQuery      Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function where(Array $attributes = null){
			$this->whereAttributes = $attributes;
			return $this;
		}

		/**
		 * Метод устанавливает какие имена столбцов нужно вывести в запросе
		 * @param  Array|null $keys  Массив параметров ['key1',...]
		 * @return SelectNativeQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function keys(Array $keys = null){
			$this->keys = $keys;
			return $this;
		}

		/**
		 * Метод устанавливает какие имена столбцов нужно вывести в запросе
		 * @param  String  $column   Имя столбца
		 * @param  Boolean $sortDesc Сортировать в порядке убывания или возрастания
		 * @return SelectNativeQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function order($column,$sortDesc = false){
			$this->orderColumn = [$column,$sortDesc];
			return $this;
		}

		/**
		 * Метод устанавливает порядковый номер вывода данных, и их колличество
		 * @param  Integer  $limitStart Номер с которого начинать выводить данные 
		 *                              Если не передан limitRange то данная переменная 
		 *                              выступает как колличество результатов
		 * @param  Boolean  $limitRange Сколько значений выводить
		 * @return SelectNativeQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function limit($limitStart,$limitRange = null){
			$this->limit = [$limitStart];
			if($limitRange !== null){
				$this->limit[1] = $limitRange;
			}
			return $this;
		}

		/**
		 * Если заданные данные WHERE, то преобразовать их в строку SQL
		 * @return String
		 */
		protected function isWhereThenGetStrQuery(){
			if(isset($this->whereAttributes)&&!empty($this->whereAttributes)){
				$queryString = '';
				foreach($this->whereAttributes as $k => $v){
					$queryString .= "$k = :$k";
				}
				return " WHERE $queryString ";
			}else{
				return '';
			}
		}

		/**
		 * Если заданные данные для сортировки, то преобразовать ее в строку SQL
		 * @return String
		 */
		protected function isOrderThenGetStrQuery(){
			if(isset($this->orderColumn)&&!empty($this->orderColumn)){
				$str = " ORDER BY {$this->orderColumn[0]} ".($this->orderColumn[1]?' DESC ':' ');
				return $str;
			}else{
				return '';
			}
		}

		/**
		 * Если заданные данные для сортировки, то преобразовать ее в строку SQL
		 * @return String
		 */
		protected function isLimitThenGetStrQuery(){
			if(isset($this->limit)&&!empty($this->limit)){
				print_r($this->limit);
				$str = " LIMIT ".(implode(" , ",$this->limit));
				return $str;
			}else{
				return '';
			}
		}

		/**
		 * Если заданные данные Keys, то преобразовать их в строку SQL
		 * @return String
		 */
		protected function isKeysThenGetStrQuery(){
			if(isset($this->keys)&&!empty($this->keys)){
				return " ".implode(" , ",$this->keys)." ";
			}else{
				return ' * ';
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
					$valueQuery[":$k"] = $v;
				}
				return $valueQuery;
			}else{
				return null;
			}
		}

		/**
		 * Метод строит на основании имеющегося запроса, строку SQL с запросом к БД
		 * @return String Строка с запросом
		 */
		protected function buildQueryString(){
			$queryString  = ' SELECT ';
			$queryString .= $this->isKeysThenGetStrQuery();
			$queryString .= ' FROM ';
			$queryString .= $this->isTableNameThenGetStrQuery();
			$queryString .= $this->isWhereThenGetStrQuery();
			$queryString .= $this->isOrderThenGetStrQuery();
			$queryString .= $this->isLimitThenGetStrQuery();

			return $queryString;
		}

		/**
		 * Метод который делает запрос в БД, и возвращает результат
		 * @return [type] [description]
		 */
		public function execute(){
			$STH =  $this->getDBHandler()->prepare($this->buildQueryString());
			print($this->buildQueryString());
			print "\n";
			$STH->execute($this->isWhereThenGetValueQuery());
			$mw = [];
			$STH->setFetchMode(PDO::FETCH_ASSOC);
			while($obj = $STH->fetch()){
				$mw[] = $obj;
			}
			return $mw;
		}

	}

	class SelectModelQuery{
		
	}