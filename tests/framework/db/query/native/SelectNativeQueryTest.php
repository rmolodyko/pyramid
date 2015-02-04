<?php

	namespace pyramid\test\db\query\native;
	use \pyramid\db\query\native\SelectNativeQuery;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class SelectNativeQueryTest extends \pyramid\test\db\query\SelectQueryAbstract{

		public function getObjQuery(){
			return new SelectNativeQuery('movies');
		}
	}