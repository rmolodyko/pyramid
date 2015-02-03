<?php

	namespace framework\helper;
	use \app\config\ConfRegister;

	require_once("/var/www/pyramid/application/config/ConfRegister.php");

	spl_autoload_register(function($class_name){
		/*$class_name = '\1\22\333';
		$class_name = '33';*/
		$class_name = preg_replace('/.*\\\/i','',$class_name);
		
		//die($class_name);
		foreach(ConfRegister::getParam('include_path') as $v){
			$pathToClass = ConfRegister::getParam('base_path').$v.DIRECTORY_SEPARATOR.$class_name.'.php';
			if(file_exists($pathToClass)){
				require_once($pathToClass);
			}
		}
		foreach(ConfRegister::getParam('include_path') as $v){
			$pathToClass = ConfRegister::getParam('base_path_to_test').$v.DIRECTORY_SEPARATOR.$class_name.'.php';
			if(file_exists($pathToClass)){
		// 		print_r($class_name);
		// print("\n");
				require_once($pathToClass);
			}
		}
	});