<?php

	namespace test\framework\orm\query;
	use \framework\orm\query\SelectNativeQuery;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class SelectNativeQueryTest extends SelectQueryAbstract{

		public function getObjQuery(){
			return new SelectNativeQuery('movies');
		}
	}