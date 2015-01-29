<?php

	namespace Test\Helper;
	require_once('ConfRegister.php');

	use Test\Config\ConfRegister;
	use PDO;

	class TestHelper{

		static public function getDBHandler(){
			try{
				$DBH = new PDO(ConfRegister::getParam('path_init_db'),
								ConfRegister::getParam('user_db'),
								ConfRegister::getParam('password_db'));
				$DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				return $DBH;
			}catch(PDOException $e){
				echo $e->getMessage();  
			}
		}

		public function findAll(){
			$STH = self::getDBHandler()->prepare('SELECT movies.* FROM movies');
			$STH->execute();
			$mw = [];
			$STH->setFetchMode(PDO::FETCH_ASSOC);
			while($obj = $STH->fetch()){
				$mw[] = $obj;
			}
			return $mw;
		}

		public function createData(){
			$STH = self::getDBHandler()->prepare('INSERT INTO movies (title,year,format) VALUES ("MyFilm",2000,"DVD")');
			$STH->execute();
			$STH = self::getDBHandler()->prepare('INSERT INTO movies (title,year,format) VALUES ("Friends",1995,"Blu-Ray")');
			$STH->execute();
			$STH = self::getDBHandler()->prepare('INSERT INTO movies (title,year,format) VALUES ("Ocean",2001,"VHS")');
			$STH->execute();
			$STH = self::getDBHandler()->prepare('INSERT INTO movies (title,year,format) VALUES ("City",2008,"VHS")');
			$STH->execute();
		}

		public function deleteData(){
			$STH = self::getDBHandler()->prepare('DELETE FROM movies');
			$STH->execute();
		}
	}