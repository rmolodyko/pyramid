<?php

	class DBHelper{

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
	}