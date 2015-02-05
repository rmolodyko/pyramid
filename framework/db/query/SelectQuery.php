<?php

	namespace pyramid\db\query;
	/**
	 * Класс предназначен для выборки данных с БД
	 * @todo Протестировать функционал Single
	 */
	abstract class SelectQuery extends Query{

		/**
		 * Хранит имя таблицы с которой будет происходить связанный запрос
		 * @var String
		 */
		protected $relatedTable;

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
		 * Данные для ON строки запроса
		 * @var Array
		 */
		protected $onAttributes;

		/**
		 * Алиас имени первой таблицы [':first:.id'...]
		 * @var String
		 */
		protected $aliasFirstTable = ':first:';

		/**
		 * Алиас имени второй таблицы
		 * @var String
		 */
		protected $aliasSecondTable = ':second:';

		/**
		 * Разделитель алиаса выводимых данных
		 * @var String
		 */
		protected $separatorColumnRequest = '_';

		/**
		 * Метод устанавливает какие имена столбцов нужно вывести в запросе
		 * @param  Array|null $keys  Массив параметров ['key1',...], разрешается передавать пустой массив
		 * @return SelectQuery Возвращает объект для дальнейшего вызова методов над ним
		 * @todo Добавить поддержку задавать поля связанной таблицы
		 */
		public function keys(Array $keys = null){
			$this->keys = $keys;
			return $this;
		}

		/**
		 * Метод устанавливает вывести один результат запроса или множество
		 * @param  Boolean $k Выводить один результат или нет
		 * @return SelectQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function single($k = false){
			if($k)
				$this->limit(0,1);
			return $this;
		}

		/**
		 * Метод устанавливает имя связанной таблицы
		 * @param  String $with Имя связанной таблицы в БД
		 * @return SelectQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function with($with = null){
			if($with !== null){
				$this->relatedTable = strtolower(trim($with));
			}else{
				throw new \Exception("Ожидалось имя таблицы");
				
			}
			return $this;
		}

		/**
		 * Метод устанавливает параметры для ON строки запроса
		 * Строка ON поддерживает только AND оператор
		 * @param  Array $on   Массив параметров в котором используются псевдонимы таблиц [':first:.id'...]
		 *                     Если значение передано как массив то его экранирование не будет происходить
		 *                     Пример: [':second:.id_movie' => 270, ':first:.id' => [':second:.id_star']]
		 * @return SelectQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function on(Array $on = null){
			$this->onAttributes = $on;
			return $this;
		}

		/**
		 * Метод устанавливает какие имена столбцов нужно вывести в запросе
		 * @param  String|Integer  $column   Имя столбца, или номер столбца
		 * @param  Boolean $sortDesc Сортировать в порядке убывания или возрастания
		 * @return SelectQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function order($column,$sortDesc = false){
			if((!is_string($column)&&!is_numeric($column))||(!is_bool($sortDesc))){
				throw new \Exception("Переданны неверные данные");
			}
			$this->orderColumn = [$column,$sortDesc];
			return $this;
		}

		/**
		 * Метод устанавливает порядковый номер вывода данных, и их колличество
		 * @param  Integer  $limitStart Номер с которого начинать выводить данные 
		 *                              Если не передан limitRange то данная переменная 
		 *                              выступает как колличество результатов
		 * @param  Integer  $limitRange Сколько значений выводить
		 * @return SelectQuery Возвращает объект для дальнейшего вызова методов над ним
		 */
		public function limit($limitStart,$limitRange = null){
			if(!is_numeric($limitStart)){
				throw new \Exception("Переданны неверные данные");
			}
			$this->limit = [$limitStart];
			if($limitRange !== null){
				if(!is_numeric($limitRange)){
					throw new \Exception("Переданны неверные данные");
				}
				$this->limit[1] = $limitRange;
			}
			return $this;
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
		 * Если заданные данные для связанного запроса то вывести имя связанной таблицы
		 * @return String
		 */
		protected function isWithThenGetStrQuery(){
			if(isset($this->relatedTable)&&!empty($this->relatedTable)){
				return $this->relatedTable;
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
				foreach($this->keys as $key => $value){
					if(!is_string($value)) throw new \Exception("Переданны неверные данные");
				}
				return " {$this->isTableNameThenGetStrQuery()}.".implode(" , {$this->isTableNameThenGetStrQuery()}.",$this->keys)." ";
			}else{
				return " {$this->isTableNameThenGetStrQuery()}.* ";
			}
		}

		/**
		 * Если заданные данные ON, то преобразовать их в строку SQL
		 * @return String
		 */
		protected function isOnThenGetStrQuery(){
			if(isset($this->onAttributes)&&!empty($this->onAttributes)){
				$arrayAttr = [];
				foreach($this->onAttributes as $k => $v){
					if(is_array($v)){
						if(isset($v[0])&&!empty($v[0])){
							$arrayAttr[] = "$k = ".$v[0];
						}else{
							throw new \Exception("Неверное имя таблицы или псевдоним");
						}
					}else{
						$arrayAttr[] = "$k = :".str_replace(".","_",$k)."_unique";
					}
				}
				return " ON ".implode(' AND ',$arrayAttr)." ";
			}else{
				return '';
			}
		}

		/**
		 * Если заданные данные ON, то преобразовать массив пригодный для скармливания PDO::execute()
		 * @return String
		 */
		protected function isOnThenGetValueQuery(){
			if(isset($this->onAttributes)&&!empty($this->onAttributes)){
				$valueQuery = [];
				$firstTableName = $this->isTableNameThenGetStrQuery();
				$secondTableName = $this->isWithThenGetStrQuery();
				foreach($this->onAttributes as $k => $v){
					if(is_array($v)) continue;
					$new_key = str_replace($this->aliasFirstTable,trim($firstTableName),$k);
					$new_key = str_replace($this->aliasSecondTable,trim($secondTableName),$new_key);
					$valueQuery[str_replace(".","_",$new_key)."_unique"] = $v;
				}
				return $valueQuery;
			}else{
				return null;
			}
		}

		/**
		 * Переопределяем родительский метод что бы добавить возможность использования алиасов таблиц
		 * @return Array|null
		 */
		protected function isWhereThenGetValueQuery(){
			if(isset($this->whereAttributes)&&!empty($this->whereAttributes)){
				$arrAttr = parent::isWhereThenGetValueQuery();
				$newArrayAttr = [];
				$firstTableName = $this->isTableNameThenGetStrQuery();
				$secondTableName = $this->isWithThenGetStrQuery();
				foreach($arrAttr as $k => $v){
					$new_key = str_replace($this->aliasFirstTable,trim($firstTableName),$k);
					$new_key = str_replace($this->aliasSecondTable,trim($secondTableName),$new_key);
					$newArrayAttr[$new_key] = $v;
				}
				return $newArrayAttr;
			}else{
				return null;
			}
		}

		/**
		 * Получить массив имен столбцов таблицы
		 * @return Array
		 */
		protected function getColumnForRelatedTable(){

			$STH =  $this->getDBHandler()->prepare("SHOW COLUMNS FROM {$this->relatedTable}");
			$STH->execute();
			$mw = [];
			$STH->setFetchMode(\PDO::FETCH_ASSOC);
			while($obj = $STH->fetch()){
				$mw[] = $obj['Field'];
			}
			return $mw;
		}

		/**
		 * Получаем кусок SQL который содержит перебиндены алиасы столбцов связанной таблицы
		 * @return String
		 */
		protected function getAliasColumnForRelatedTable(){
			$arrayColumn = $this->getColumnForRelatedTable();
			foreach($arrayColumn as $key => &$value){
				$value = "{$this->aliasSecondTable}.{$value} as {$value}{$this->separatorColumnRequest}{$this->aliasSecondTable}";
			}
			return implode(" , ",$arrayColumn);
		}

		/**
		 * Метод строит на основании имеющегося запроса, строку SQL с запросом к БД
		 * @return String Строка с запросом
		 */
		protected function buildQueryString(){

			if($this->relatedTable === null){//Простой запрос
				$queryString  = ' SELECT ';
				$queryString .= $this->isKeysThenGetStrQuery();
				$queryString .= ' FROM ';
				$queryString .= $this->isTableNameThenGetStrQuery();
				$queryString .= $this->isWhereThenGetStrQuery();
				$queryString .= $this->isOrderThenGetStrQuery();
				$queryString .= $this->isLimitThenGetStrQuery();

			}else{//Связанный запрос

				$firstTableName = $this->isTableNameThenGetStrQuery();
				$secondTableName = $this->isWithThenGetStrQuery();
				$queryString  = ' SELECT ';
				$queryString .= $this->isKeysThenGetStrQuery()." , ".$this->getAliasColumnForRelatedTable();
				$queryString .= ' FROM ';
				$queryString .= $firstTableName;
				$queryString .= ' INNER JOIN ';
				$queryString .= $secondTableName;
				$queryString .= $this->isOnThenGetStrQuery();
				$queryString .= $this->isWhereThenGetStrQuery();
				$queryString .= $this->isOrderThenGetStrQuery();
				$queryString .= $this->isLimitThenGetStrQuery();

				//Меняем алиасы на имена таблиц
				$queryString = str_replace($this->aliasFirstTable,trim($firstTableName),$queryString);
				$queryString = str_replace($this->aliasSecondTable,trim($secondTableName),$queryString);
			}

			return $queryString;
		}

		/**
		 * Метод который делает запрос в БД, и возвращает результат
		 * @return [type] [description]
		 * @todo  Сделать обертку, она должна корректно работать с ф. count
		 *        В тестах добавить использование обертки
		 */
		public function execute(){
			//print_r($this->buildQueryString());
			$STH =  $this->getDBHandler()->prepare($this->buildQueryString());

			//Сливаем bind параметры разных видов запроса в один массив
			$arr1 = $this->isOnThenGetValueQuery();
			$arr2 = $this->isWhereThenGetValueQuery();
			$arr1 = is_array($arr1) ? $arr1 : [];
			$arr2 = is_array($arr2) ? $arr2 : [];
			//print_r(array_merge($arr1,$arr2));
			$STH->execute(array_merge($arr1,$arr2));
			$mw = [];
			$STH->setFetchMode(\PDO::FETCH_ASSOC);
			while($obj = $STH->fetch()){
				$mw[] = $obj;
			}
			return $mw;
		}

	}