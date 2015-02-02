<?php
  
	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class UpdateQueryAbstract extends DBTestClass{

		public function testIfChangeRow(){
			$dataBefore = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			$this->getObjQuery()->set(['id'=>1,'title'=>'New movies','format'=>'DVD','year'=>9999])->where(['id'=>1])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			

			$this->assertNotEquals($dataBefore,$dataAfter);
		}

		/**
		 * Тест потребует ручной настройки при изменении вывода результата SelectQuery
		 */
		public function testManualySettupOut(){
			$dataBefore = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			$this->getObjQuery()->set(['title'=>'New movies','format'=>'VHS','year'=>9999])->where(['id'=>1])->execute();
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
			$this->getObjQuery()->set(['title'=>'New movies','format'=>'DVD','year'=>9999])->where(['id'=>1001])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->execute();

			$this->assertEquals($dataBefore,$dataAfter);
		}

		public function testIfNotWholeData(){
			$dataBefore = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			$this->getObjQuery()->set(['year'=>9999])->where(['id'=>1])->execute();
			$dataAfter = (new SelectNativeQuery('movies'))->where(['id'=>1])->execute();
			

			$this->assertNotEquals($dataBefore,$dataAfter);
		}

		public function testError(){

			try{
				$this->expectError(1);
				$this->getObjQuery()->set(['titleooo'=>'New movies','format'=>'DVD','year'=>9999])->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$this->getObjQuery()->set(['id'=>2,'title'=>'New movies','format'=>'DVD','year'=>1111])->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$this->getObjQuery()->set(['title'=>'New movies','format'=>'DVD','year'=>[]])->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$this->getObjQuery()->set([])->where(['id'=>1])->execute();
				
			}catch(Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);
				$this->getObjQuery()->set(['title'=>'New movies','format'=>'DVD','year'=>9999])->where([])->execute();
			}catch(Exception $e){
				$this->issuedError(5);
			}

			try{
				$this->expectError(6);
				$this->getObjQuery()->set()->where(['id'=>1])->execute();
			}catch(Exception $e){
				$this->issuedError(6);
			}

			try{
				$this->expectError(7);
				$this->getObjQuery()->set(['id'=>1,'title'=>'New movies','format'=>'DVD','year'=>9999])->where([])->execute();
			}catch(Exception $e){
				$this->issuedError(7);
			}

			try{
				$this->expectError(8);
				$this->getObjQuery()->set(['id'=>1,'title'=>'New movies','format'=>'DVD','year'=>9999])->where()->execute();
			}catch(Exception $e){
				$this->issuedError(8);
			}

			try{
				$this->expectError(9);
				$this->getObjQuery()->set(['id'=>1,'title'=>'New movies','format'=>'DVD','year'=>9999])->execute();
			}catch(Exception $e){
				$this->issuedError(9);
			}

			try{
				$this->expectError(10);
				$this->getObjQuery()->set(['title'=>'New movies','format'=>'DVD','year'=>9999])->execute();
			}catch(Exception $e){
				$this->issuedError(10);
			}

			try{
				$this->expectError(11);
				$this->getObjQuery()->set()->execute();
			}catch(Exception $e){
				$this->issuedError(11);
			}

			try{
				$this->expectError(12);
				$this->getObjQuery()->execute();
			}catch(Exception $e){
				$this->issuedError(12);
			}

			$this->throwErrors();
		}
	}