<?php

	require_once(dirname(__FILE__).'/../../lib/include.php');

	class SelectNativeQueryTest extends DBTestClass{

		public function testSelectAll(){

			$needData = $this->execQuery('SELECT * FROM movies');
			$newData = (new SelectNativeQuery('movies'))->execute();
			$this->assertEquals($needData,$newData);

		}

		public function testSelectLimit(){

			$needData = $this->execQuery('SELECT * FROM movies LIMIT 0,1');
			$newData = (new SelectNativeQuery('movies'))->limit(0,1)->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies LIMIT 2,2');
			$newData = (new SelectNativeQuery('movies'))->limit(2,2)->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies LIMIT 2');
			$newData = (new SelectNativeQuery('movies'))->limit(2)->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = (new SelectNativeQuery('movies'))->limit()->execute();
			}catch(Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = (new SelectNativeQuery('movies'))->limit([])->execute();
			}catch(Exception $e){
				$this->issuedError(2);
			}
			$this->throwErrors();
		}
}