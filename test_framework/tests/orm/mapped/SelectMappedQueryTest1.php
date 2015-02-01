<?php

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class SelectMappedQueryTest extends SelectQueryAbstract{

		public function getObjQuery(){
			return new SelectMappedQuery(new Movies);
		}
	}