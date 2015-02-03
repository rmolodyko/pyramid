<?php

	namespace test\framework\orm\query;
	use \framework\orm\query\InsertNativeQuery;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class InsertNativeQueryTest extends InsertQueryAbstract{

		public function getObjQuery(){
			return new InsertNativeQuery('movies');
		}
	}