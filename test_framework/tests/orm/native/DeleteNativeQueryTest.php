<?php
 
	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class DeleteNativeQueryTest extends DeleteQueryAbstract{

		public function getObjQuery(){
			return new DeleteNativeQuery('movies');
		}

	}