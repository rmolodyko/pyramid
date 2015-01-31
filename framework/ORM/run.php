<?php

	require_once("/var/www/other/ORM/helper/ConfRegister.php");

	print "\n";
	spl_autoload_register(function($class_name){
		foreach(ConfRegister::getParam('include_path') as $v){
			$pathToClass = ConfRegister::getParam('base_path').$v.DIRECTORY_SEPARATOR.$class_name.'.php';
			if(file_exists($pathToClass)){
				require_once($pathToClass);
			}
		}
	});

/* ---------------------------------------- */

	print "\n";


	class Model{
		public $uuu = 1;
	}

	class Movies extends Model{
		public $title = 'Title';
		public $year = 8989;
		public $format = 'DVD';
	}

	/*$last_id = (new InsertMappedQuery(new Movies))->execute();
	print_r($last_id);
	print_r((new SelectNativeQuery('movies'))->where(['id'=>$last_id])->execute());
	
	print_r((new DeleteMappedQuery(new Movies))->where(['id'=>$last_id])->execute());
	print "----\n";
*/
	//(new UpdateMappedQuery(new Movies))->where(['id'=>262])->execute();
	print_r(
		(new SelectNativeQuery('stars'))
		->with('movie_star')
		->on([':second:.id_movie' => 270, ':first:.id' => [':second:.id_star']])
		->limit(0,3)->order(':first:.id')
		->execute()
		);