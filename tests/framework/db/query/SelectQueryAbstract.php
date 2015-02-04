<?php

	namespace pyramid\test\db\query;

	require_once(dirname(__FILE__).'/../../lib/include.php');

	class SelectQueryAbstract extends \pyramid\test\lib\DBTestClass{

		public function testSelectAll(){

			$needData = $this->execQuery('SELECT * FROM movies');
			$newData = $this->getObjQuery()->execute();
			$this->assertEquals($needData,$newData);

		}

		public function testSelectLimit(){

			$needData = $this->execQuery('SELECT * FROM movies LIMIT 0,1');
			$newData = $this->getObjQuery()->limit(0,1)->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies LIMIT 2,2');
			$newData = $this->getObjQuery()->limit(2,2)->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies LIMIT 2');
			$newData = $this->getObjQuery()->limit(2)->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()->limit(-1)->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()->limit([])->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$newData = $this->getObjQuery()->limit(-1)->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$newData = $this->getObjQuery()->limit(1,-1)->execute();
			}catch(\Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);
				$newData = $this->getObjQuery()->limit("sfs")->execute();
			}catch(\Exception $e){
				$this->issuedError(5);
			}

			try{
				$this->expectError(6);
				$newData = $this->getObjQuery()->limit(1,"sfs")->execute();
			}catch(\Exception $e){
				$this->issuedError(6);
			}

			$this->throwErrors();
		}

		public function testSelectOrder(){

			$needData = $this->execQuery('SELECT * FROM movies ORDER BY id DESC');
			$newData = $this->getObjQuery()->order('id',true)->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies ORDER BY title');
			$newData = $this->getObjQuery()->order('title')->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies ORDER BY id');
			$newData = $this->getObjQuery()->order('id')->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies ORDER BY year');
			$newData = $this->getObjQuery()->order('year')->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()->order('')->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()->order(null)->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$newData = $this->getObjQuery()->order('year',[])->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$newData = $this->getObjQuery()->order()->execute();
			}catch(\Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);
				$newData = $this->getObjQuery()->order(20)->execute();
				print_r($newData);
			}catch(\Exception $e){
				$this->issuedError(5);
			}

			$this->throwErrors();
		}

		public function testSelectWhere(){

			$needData = $this->execQuery('SELECT * FROM movies WHERE id = 2');
			$newData = $this->getObjQuery()->where(['id'=>2])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT * FROM movies WHERE format = 'DVD'");
			$newData = $this->getObjQuery()->where(['format'=>'DVD'])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies WHERE id = 100');
			$newData = $this->getObjQuery()->where(['id'=>100])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT * FROM movies WHERE format = 'DVD' AND year = 2000");
			$newData = $this->getObjQuery()->where(['format'=>'DVD','year'=>2000])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT * FROM movies");
			$newData = $this->getObjQuery()->where([])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT * FROM movies");
			$newData = $this->getObjQuery()->where()->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT * FROM movies WHERE format = 'DVD' AND year = null");
			$newData = $this->getObjQuery()->where(['format'=>'DVD','year'=>null])->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()->where(['format'=>'DVD','year'=>[2000]])->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()->where(['format'=>'DVD','rtyy'=>2000])->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}
			try{
				$this->expectError(3);
				$newData = $this->getObjQuery()->where(['rty'=>[2000]])->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			$this->throwErrors();
		}

		public function testSelectKeys(){

			$needData = $this->execQuery('SELECT id FROM movies');
			$newData = $this->getObjQuery()->keys(['id'])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT id,title FROM movies');
			$newData = $this->getObjQuery()->keys(['id','title'])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT * FROM movies');
			$newData = $this->getObjQuery()->keys([])->execute();
			$this->assertEquals($needData,$newData);
			
			$needData = $this->execQuery('SELECT * FROM movies');
			$newData = $this->getObjQuery()->keys()->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()->keys([null])->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()->keys([[]])->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$newData = $this->getObjQuery()->keys('dsf')->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$newData = $this->getObjQuery()->keys(0)->execute();
			}catch(\Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);
				$newData = $this->getObjQuery()->keys(['dsff'])->execute();
			}catch(\Exception $e){
				$this->issuedError(5);
			}

			try{
				$this->expectError(6);
				$newData = $this->getObjQuery()->keys(['id',null])->execute();
			}catch(\Exception $e){
				$this->issuedError(6);
			}

			$this->throwErrors();

		}

		public function testSelectWith(){

			$needData = $this->execQuery("SELECT movies .* , movie_star.id as id_movie_star , 
				movie_star.id_movie as id_movie_movie_star , movie_star.id_star as id_star_movie_star 
				FROM movies INNER JOIN movie_star");
			$newData = $this->getObjQuery()
				->with('movie_star')
				->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()
				->with('movie_star8')
				->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()
				->with()
				->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$newData = $this->getObjQuery()
				->with([])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$newData = $this->getObjQuery()
				->with(null)
				->execute();
			}catch(\Exception $e){
				$this->issuedError(4);
			}

			$this->throwErrors();
		}

		public function testSelectWithOn(){

			$needData = $this->execQuery('SELECT movies .* , movie_star.id as id_movie_star , 
				movie_star.id_movie as id_movie_movie_star , movie_star.id_star as id_star_movie_star 
				FROM movies INNER JOIN movie_star ON movie_star.id_movie = 2 AND movies.id = movie_star.id_star');
			$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie" => 2,":first:.id" => [":second:.id_star"]])
				->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT movies .* , movie_star.id as id_movie_star , 
				movie_star.id_movie as id_movie_movie_star , movie_star.id_star as id_star_movie_star 
				FROM movies INNER JOIN movie_star ON movies.format = 'DVD' AND movies.id = movie_star.id_star");
			$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":first:.format" => 'DVD',":first:.id" => [":second:.id_star"]])
				->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":first:.format" => ['DVD'],":first:.id" => [":second:.id_star"]])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":firs:.format" => null,":first:.id" => [":second:.id_star"]])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}
			try{
				$this->expectError(3);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":first:.format" => [],":first:.id" => [":second:.id_star"]])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}
			try{
				$this->expectError(4);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on("kjkj")
				->execute();
			}catch(\Exception $e){
				$this->issuedError(4);
			}
			try{
				$this->expectError(5);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([[]])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(5);
			}

			$this->throwErrors();
		}

		public function testSelectWithout_On(){

			$newData = $this->getObjQuery()
				->on(['id'=>1])
				->execute();

			$newData = $this->getObjQuery()
				->on()
				->execute();
		}

		public function testSelectMixedWithout_With(){

			$needData = $this->execQuery('SELECT * FROM movies WHERE id = 2 ORDER BY id');
			$newData = $this->getObjQuery()->where(['id'=>2])->order('id')->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery('SELECT id FROM movies WHERE id = 2 ORDER BY id');
			$newData = $this->getObjQuery()->keys(['id'])->where(['id'=>2])->order('id')->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT id FROM movies WHERE format = 'DVD' ORDER BY format");
			$newData = $this->getObjQuery()->keys(['id'])->where(['format'=>'DVD'])->order('format')->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT id FROM movies WHERE format = 'DVD' ORDER BY id LIMIT 0,1 ");
			$newData = $this->getObjQuery()->keys(['id'])->where(['format'=>'DVD'])->limit(0,1)->order('id')->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT id FROM movies WHERE format = 'DVD' ORDER BY id LIMIT 0,1 ");
			$newData = $this->getObjQuery()->limit(0,1)->where(['format'=>'DVD'])->order('id')->keys(['id'])->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT id FROM movies ORDER BY id LIMIT 0,10 ");
			$newData = $this->getObjQuery()->keys(['id'])->limit(0,10)->order('id')->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT id FROM movies ORDER BY id DESC LIMIT 0,10 ");
			$newData = $this->getObjQuery()->keys(['id'])->limit(0,10)->order('id',true)->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()->keys(['id'])->limit(0,10)->order('id',[])->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()->limit(0,1)->where(['format'=>'DVD'])->order('id')->keys(['id2'])->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			$this->throwErrors();
		}

		public function testSelectMixedWith_With(){

			$needData = $this->execQuery("SELECT movies .* , movie_star.id as id_movie_star , 
				movie_star.id_movie as id_movie_movie_star , movie_star.id_star as id_star_movie_star 
				FROM movies INNER JOIN movie_star ON movie_star.id_movie = 2 AND movies.id = movie_star.id_star ORDER BY movies.id");
			$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie" => 2,":first:.id" => [":second:.id_star"]])
				//->where(['id'=>2])
				->order(':first:.id')
				->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT movies .* , movie_star.id as id_movie_star , 
				movie_star.id_movie as id_movie_movie_star , movie_star.id_star as id_star_movie_star 
				FROM movies INNER JOIN movie_star ON movie_star.id_movie = 2 AND movies.id = movie_star.id_star 
				WHERE movies.format = 'DVD' ORDER BY movies.id");
			$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first:.id')
				->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT movies .* , movie_star.id as id_movie_star , 
				movie_star.id_movie as id_movie_movie_star , movie_star.id_star as id_star_movie_star 
				FROM movies INNER JOIN movie_star ON movie_star.id_movie = 2 AND movies.id = movie_star.id_star 
				WHERE movies.format = 'DVD' ORDER BY movies.id LIMIT 0,1");
			$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first:.id')
				->limit(0,1)
				->execute();
			$this->assertEquals($needData,$newData);

			$needData = $this->execQuery("SELECT movies .id , movie_star.id as id_movie_star , 
				movie_star.id_movie as id_movie_movie_star , movie_star.id_star as id_star_movie_star 
				FROM movies INNER JOIN movie_star ON movie_star.id_movie = 2 AND movies.id = movie_star.id_star 
				WHERE movies.format = 'DVD' ORDER BY movies.id LIMIT 0,1");
			$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first:.id')
				->limit(0,1)
				->keys(['id'])
				->execute();
			$this->assertEquals($needData,$newData);

			try{
				$this->expectError(1);
				$newData = $this->getObjQuery()
				->with('movie_star8')
				->on([":second:.id_movie" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first:.id')
				->limit(0,1)
				->keys(['id'])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(1);
			}

			try{
				$this->expectError(2);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie4" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first:.id')
				->limit(0,1)
				->keys(['id'])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(2);
			}

			try{
				$this->expectError(3);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie" => [],":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first:.id')
				->limit(0,1)
				->keys(['id'])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(3);
			}

			try{
				$this->expectError(4);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie4" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>null])
				->order(':first:.id')
				->limit(0,1)
				->keys(['id'])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(4);
			}

			try{
				$this->expectError(5);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie4" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first:.id')
				->limit(0,1)
				->keys(['id4'])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(5);
			}

			try{
				$this->expectError(6);
				$newData = $this->getObjQuery()
				->with('movie_star')
				->on([":second:.id_movie4" => 2,":first:.id" => [":second:.id_star"]])
				->where([':first:.format'=>'DVD'])
				->order(':first33:.id')
				->limit(0,1)
				->keys(['id'])
				->execute();
			}catch(\Exception $e){
				$this->issuedError(6);
			}

			$this->throwErrors();
		}
}