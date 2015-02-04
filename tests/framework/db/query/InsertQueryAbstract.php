<?php

	namespace pyramid\test\db\query;
	use \pyramid\db\query\native\SelectNativeQuery;

	require_once(dirname(__FILE__).'/../../lib/include.php');

	class InsertQueryAbstract extends \pyramid\test\lib\DBTestClass{

		public function testCountRow(){
			$rowCountBefore = $this->getConnection()->getRowCount('movies');
			$this->getObjQuery()->values(['id'=>100,'title'=>'New movies','format'=>'DVD','year'=>9991])->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');

			$this->assertEquals($rowCountAfter,$rowCountBefore+1);
		}

		public function testCountRowWithoutId(){
			$rowCountBefore = $this->getConnection()->getRowCount('movies');
			$this->getObjQuery()->values(['title'=>'New movies','format'=>'DVD','year'=>9991])->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');

			$this->assertEquals($rowCountAfter,$rowCountBefore+1);
		}

		public function testRelevntRow(){
			$rowCountBefore = $this->getConnection()->getRowCount('movies');
			$this->getObjQuery()->values(['id'=>100,'title'=>'New movies','format'=>'DVD','year'=>9991])->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');
			$data = (new SelectNativeQuery('movies'))->where(['id'=>100])->execute();

			$this->assertEquals(count($data),1);
		}

		public function testError(){

			try{
				$this->expectError(1);
				$this->getObjQuery()
					->values(['title'=>'New movies','format'=>'DVD','year'=>9991,'fggg'=>'4'])
					->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$this->getObjQuery()
					->values([])
					->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$this->getObjQuery()->values(['id'=>100,'title'=>'New movies','format'=>'DVD','year'=>9991])->execute();
				$this->getObjQuery()->values(['id'=>100,'title'=>'New movies','format'=>'DVD','year'=>9991])->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$this->getObjQuery()->values(['id'=>null,'title'=>'New movies','format'=>'DVD','year'=>9991])->execute();
				
			}catch(\Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);
				$this->getObjQuery()->execute();
			}catch(\Exception $e){
				$this->issuedError(5);
			}

			try{
				$this->expectError(6);
				$this->getObjQuery()->values(['title'=>'New movies','format'=>'DVD','year'=>null])->execute();
			}catch(\Exception $e){
				$this->issuedError(6);
			}

			try{
				$this->expectError(7);
				$this->getObjQuery()->values(['title'=>'New movies','format','year'=>'1000'])->execute();
			
			}catch(\Exception $e){
				$this->issuedError(7);
			}

			$this->throwErrors();
		}
	}