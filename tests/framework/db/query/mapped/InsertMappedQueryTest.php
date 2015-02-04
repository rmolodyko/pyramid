<?php

	namespace pyramid\test\db\query\mapped;
	use \pyramid\db\query\mapped\InsertMappedQuery;
	use \pyramid\db\query\native\SelectNativeQuery;
	use \pyramid\test\db\model\Movies;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class SampleClassFotTest{}

	class InsertMappedQueryTest extends \pyramid\test\db\query\InsertQueryAbstract{

		public function getObjQuery(){
			$obj = new Movies;
			return new InsertMappedQuery(new Movies);
		}

		public function testCountRow(){
			$rowCountBefore = $this->getConnection()->getRowCount('movies');

			$obj = new Movies;
			$obj->title = 'New movies';
			$obj->format = 'DVD';
			$obj->year = 9991;

			(new InsertMappedQuery($obj))->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');

			$this->assertEquals($rowCountAfter,$rowCountBefore+1);
		}

		public function testCountRowWithoutId(){
			$rowCountBefore = $this->getConnection()->getRowCount('movies');

			$obj = new Movies;
			$obj->title = 'New movies';
			$obj->format = 'DVD';
			$obj->year = 9991;

			(new InsertMappedQuery($obj))->execute();

			$rowCountAfter = $this->getConnection()->getRowCount('movies');

			$this->assertEquals($rowCountAfter,$rowCountBefore+1);
		}

		public function testRelevntRow(){
			$rowCountBefore = $this->getConnection()->getRowCount('movies');
			
			$obj = new Movies;
			$obj->title = 'New movies';
			$obj->format = 'DVD';
			$obj->year = 2345;

			(new InsertMappedQuery($obj))->execute();

			$data = (new SelectNativeQuery('movies'))->where(['year'=>2345])->execute();
			$this->assertEquals(count($data),1);
		}

		public function testErrorMapping(){

			try{
				$this->expectError(1);

				$obj = new Movies;
				$obj->title = 'New movies';
				$obj->format = 'DVD';
				$obj->year = null;

				(new InsertMappedQuery($obj))->execute();

			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);

				$obj = new Movies;
				$obj->title = 'New movies';
				$obj->format = 'DVD';
				$obj->year = null;
				$obj->rrrrr = '';

				(new InsertMappedQuery($obj))->execute();

			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);

				$obj = new Movies;
				(new InsertMappedQuery($obj))->execute();

			}catch(\Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);

				(new InsertMappedQuery(new SampleClassFotTest))->execute();

			}catch(\Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);

				(new InsertMappedQuery(null))->execute();

			}catch(\Exception $e){
				$this->issuedError(5);
			}

			try{
				$this->expectError(6);

				(new InsertMappedQuery())->execute();

			}catch(\Exception $e){
				$this->issuedError(6);
			}

			try{
				$this->expectError(7);

				$obj = new Movies;
				$obj->title = 'New movies';
				$obj->format = 'DVD';
				$obj->year = 2345;

				(new InsertMappedQuery(new Movies))->values(['id'=>100,'title'=>'dsfs','format'=>'DVD','year'=>1000])->execute();

			}catch(\Exception $e){
				$this->issuedError(7);
			}

			$this->throwErrors();
		}
	}