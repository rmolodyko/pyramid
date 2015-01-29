<?php

	namespace App;

	require_once("DBHelper.php");
	

	class Model extends DBHelper{

		public function findAll(){
			
			$STH =  $this->getDBHandler()->prepare('SELECT movies.* FROM movies');
			$STH->execute();
			$mw = [];
			$STH->setFetchMode(\PDO::FETCH_ASSOC);
			while($obj = $STH->fetch()){
				$mw[] = $obj;
			}
			return $mw;
		}
	}