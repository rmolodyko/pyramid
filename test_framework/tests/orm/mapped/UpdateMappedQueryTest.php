<?php
 
	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class UpdateSampleClassFotTest{}

	class UpdateMappedQueryTest extends DBTestClass{

		public function testIfChangeRow(){
			$dataBefore = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			$obj = new Movies;
			$obj->title = 'New movies';
			$obj->format = 'DVD';
			$obj->year = 9999;
			(new UpdateMappedQuery($obj))->where(['id'=>1])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			

			$this->assertNotEquals($dataBefore,$dataAfter);
		}

		/**
		 * Тест потребует ручной настройки при изменении вывода результата SelectQuery
		 */
		public function testManualySettupOut(){
			$dataBefore = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			$obj = new Movies;
			$obj->title = 'New movies';
			$obj->format = 'VHS';
			$obj->year = 9999;
			(new UpdateMappedQuery($obj))->where(['id'=>1])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			
			$dataAfter = (array)$dataAfter;
			$dataBefore = (array)$dataBefore;
			$dataAfter = $dataAfter[0];
			$dataBefore = $dataBefore[0];

			$this->assertNotEquals('New movies',$dataBefore['title']);
			$this->assertNotEquals('VHS',$dataBefore['format']);
			$this->assertNotEquals(9999,$dataBefore['year']);

			$this->assertEquals('New movies',$dataAfter['title']);
			$this->assertEquals('VHS',$dataAfter['format']);
			$this->assertEquals(9999,$dataAfter['year']);
		}

		public function testIfNotTruthId(){
			$dataBefore = (new SelectNativeQuery('movies'))->execute();
			$obj = new Movies;
			$obj->title = 'New movies';
			$obj->id = 1;
			$obj->format = 'VHS';
			$obj->year = 9999;
			(new UpdateMappedQuery($obj))->where(['id'=>1001])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->execute();

			$this->assertEquals($dataBefore,$dataAfter);
		}

		public function testIfNotWholeData(){
			$dataBefore = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			$obj = new Movies;
			$obj->year = 9999;
			(new UpdateMappedQuery($obj))->where(['id'=>1])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();

			$this->assertNotEquals($dataBefore,$dataAfter);
		}

		public function testIfNotChangeId(){
			$dataBefore = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			$obj = new Movies;
			$obj->id = 2;
			$obj->year = 9999;
			(new UpdateMappedQuery($obj))->where(['id'=>1])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();

			$this->assertNotEquals($dataBefore,$dataAfter);
		}

		public function testError(){

			try{
				$this->notExpectError(1);
				$obj = new Movies;
				$obj->titleooo = 'New movies';
				$obj->id = 1;
				$obj->format = 'VHS';
				$obj->year = 9999;
				(new UpdateMappedQuery($obj))->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$obj = new Movies;
				(new UpdateMappedQuery($obj))->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$obj = new UpdateSampleClassFotTest;
				(new UpdateMappedQuery($obj))->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$obj = new Movies;
				$obj->title = 'New movies';
				$obj->format = 'VHS';
				$obj->year = 9999;
				(new UpdateMappedQuery($obj))->execute();
			}catch(Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);
				(new UpdateMappedQuery())->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(5);
			}

			$this->throwErrors();
		}
	}