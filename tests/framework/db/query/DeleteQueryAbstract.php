<?php

	namespace pyramid\test\db\query;
	use \pyramid\db\query\native\SelectNativeQuery;

	require_once(dirname(__FILE__).'/../../lib/include.php');

	class DeleteQueryAbstract extends \pyramid\test\lib\DBTestClass{

		public function testCountRow(){
			$rowCountBefore = $this->getConnection()->getRowCount('movies');
			$this->getObjQuery()->where(['id'=>2])->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');

			$this->assertEquals($rowCountAfter,$rowCountBefore-1);
		}

		public function testDeleteAll(){

			$this->getObjQuery()->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');
			$this->assertEquals($rowCountAfter,0);
		
			$this->getObjQuery()->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');
			$this->assertEquals($rowCountAfter,0);
		
		}
		
		public function testDeleteAllWithoutParamInWhere(){

			$this->getObjQuery()->where()->execute();
			$rowCountAfter = $this->getConnection()->getRowCount('movies');
			$this->assertEquals($rowCountAfter,0);
		
		}

		public function testWhere(){

			$resBeforeExec = (new SelectNativeQuery('movies'))->where(['id'=>2])->execute();
			$wrongExec = (new SelectNativeQuery('movies'))->where(['id'=>1000])->execute();
			$this->assertNotEquals($wrongExec,$resBeforeExec);
			$this->getObjQuery()->where(['id'=>2])->execute();
			$resAfterExec = (new SelectNativeQuery('movies'))->where(['id'=>2])->execute();
			$this->assertEquals($wrongExec,$resAfterExec);

			$rowCountBefore = $this->getConnection()->getRowCount('movies');
			$resBeforeExec = (new SelectNativeQuery('movies'))->where(['format'=>'DVD','year'=>2000])->execute();
			$wrongExec = (new SelectNativeQuery('movies'))->where(['format'=>'DVD','year'=>9999])->execute();
			$this->assertNotEquals($wrongExec,$resBeforeExec);
			$this->getObjQuery()->where(['format'=>'DVD','year'=>2000])->execute();
			$resAfterExec = (new SelectNativeQuery('movies'))->where(['format'=>'DVD','year'=>2000])->execute();
			$this->assertEquals($wrongExec,$resAfterExec);
			
			$this->assertEquals($rowCountBefore-2,$this->getConnection()->getRowCount('movies'));

			try{
				$this->expectError(1);
				$this->getObjQuery()->where(0)->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$this->getObjQuery()->where("dss")->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$this->getObjQuery()->where(['id'=>['ppp']])->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			$this->throwErrors();

		}
	}