<?php

	namespace app\config;

	class ConfRegister{

		public function getParam($k){
			$param = [

				'path_init_db'=>'mysql:host=localhost;dbname=movie_db_test',
				'user_db'=>'root',
				'password_db'=>'muha1990',

				'base_path'=>'/var/www/pyramid',
			];
			return $param[$k];
		}
	}