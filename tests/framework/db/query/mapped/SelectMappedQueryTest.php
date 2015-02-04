<?php

	namespace pyramid\test\db\query\mapped;
	use \pyramid\db\query\mapped\SelectMappedQuery;
	use \pyramid\test\db\model\Movies;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class SelectMappedQueryTest extends \pyramid\test\db\query\SelectQueryAbstract{

		public function getObjQuery(){
			return new SelectMappedQuery(new Movies);
		}
	}