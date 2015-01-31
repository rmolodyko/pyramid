<?php

	class ConfRegister{

		public function getParam($k){
			$param = [
				'path_init_db'=>'mysql:host=localhost;dbname=movie_db',
				'user_db'=>'root',
				'password_db'=>'muha1990',

				'include_path'=>['abstract','helper','mapped','model','native'],
				'base_path'=>'/var/www/other/ORM/',

			];
			return $param[$k];
		}

	}