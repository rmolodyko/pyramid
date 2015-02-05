<?php

	namespace framework\helper;
	use \app\config\ConfRegister;

	require_once(__DIR__."/../../application/sample/config/ConfRegister.php");

	spl_autoload_register(function($class_name){

		$dsp = DIRECTORY_SEPARATOR;
		
		if(strrpos($class_name, 'pyramid\\test\\') === false){
			$class_name = str_replace('pyramid\\','',$class_name);
			$class_name = str_replace('\\','/',$class_name);
			
			$pathToClass = ConfRegister::getParam('base_path').$dsp.'framework'.$dsp.$class_name.'.php';
			if(file_exists($pathToClass)){
				require_once($pathToClass);
			}
		}else{
			$class_name = str_replace('pyramid\\test\\','',$class_name);
			$class_name = str_replace('\\','/',$class_name);
			//print("\n".$class_name);
			$pathToClass = ConfRegister::getParam('base_path').$dsp.'tests'.$dsp.'framework'.$dsp.$class_name.'.php';
			//print("\n".$pathToClass."\n");
			if(file_exists($pathToClass)){
				require_once($pathToClass);
			}
		}
	});


	/* ------------------------------- */

	//Пример роботы с созданным функционалом
	$movies = new \pyramid\db\model\Movies();
	//$movies->id = 112;
	$movies->title = 'RRReee11';
	$movies->format = 'DVD';
	$movies->year = 1111;
	$res1 = $movies->save();
	$res = $movies->findAllByAttributes();
	//print_r($res->getRow(0));
	foreach($res as $value){
		print_r($value);
	}