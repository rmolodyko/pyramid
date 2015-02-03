<?php

	namespace app\config;
	class ConfRegister{

		public function getParam($k){
			$param = [
				'path_init_db'=>'mysql:host=localhost;dbname=movie_db_test',
				'user_db'=>'root',
				'password_db'=>'muha1990',

				'include_path'=>['abstract','helper','mapped','model','native'],
				'base_path'=>'/var/www/pyramid/framework/orm/',
				'base_path_to_test'=>'/var/www/pyramid/test_framework/tests/orm/',

			];
			return $param[$k];
		}
	}