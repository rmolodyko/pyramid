<?php

	class ConfRegister{

		public function getParam($k){
			$param = [
				'path_init_db'=>'mysql:host=localhost;dbname=movie_db',
				'user_db'=>'root',
				'password_db'=>'muha1990',

			];
			return $param[$k];
		}

	}