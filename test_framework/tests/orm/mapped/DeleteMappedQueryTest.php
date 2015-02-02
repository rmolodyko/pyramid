<?php
 
	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class DeleteMappedQueryTest extends DeleteQueryAbstract{

		public function getObjQuery(){
			return new DeleteMappedQuery(new Movies);
		}
	}