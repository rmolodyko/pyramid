<?php
 
	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class SelectNativeQueryTest extends SelectQueryAbstract{

		public function getObjQuery(){
			return new SelectNativeQuery('movies');
		}
	}