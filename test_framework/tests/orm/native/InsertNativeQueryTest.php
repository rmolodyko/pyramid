<?php
 
	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class InsertNativeQueryTest extends InsertQueryAbstract{

		public function getObjQuery(){
			return new InsertNativeQuery('movies');
		}
	}