<?php

	namespace test\framework\orm\query;
	use \framework\orm\query\DeleteNativeQuery;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class DeleteNativeQueryTest extends DeleteQueryAbstract{

		public function getObjQuery(){
			return new DeleteNativeQuery('movies');
		}

	}