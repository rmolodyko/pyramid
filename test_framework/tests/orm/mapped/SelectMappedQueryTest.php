<?php

	namespace test\framework\orm\query;
	use \framework\orm\query;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class SelectMappedQueryTest extends SelectQueryAbstract{

		public function getObjQuery(){
			return new query\SelectMappedQuery(new Movies);
		}
	}