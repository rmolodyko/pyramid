<?php

	require_once("/var/www/pyramid/application/config/ConfRegister.php");

	spl_autoload_register(function($class_name){
		foreach(ConfRegister::getParam('include_path') as $v){
			$pathToClass = ConfRegister::getParam('base_path').$v.DIRECTORY_SEPARATOR.$class_name.'.php';
			if(file_exists($pathToClass)){
				require_once($pathToClass);
			}
		}
	});