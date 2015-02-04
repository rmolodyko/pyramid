<?php

	namespace pyramid\test\db\query\native;
	use \pyramid\db\query\native\InsertNativeQuery;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class InsertNativeQueryTest extends \pyramid\test\db\query\InsertQueryAbstract{

		public function getObjQuery(){
			return new InsertNativeQuery('movies');
		}
	}