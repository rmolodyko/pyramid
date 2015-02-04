<?php

	namespace pyramid\test\db\query\mapped;
	use \pyramid\db\query\mapped\DeleteMappedQuery;
	use \pyramid\test\db\model\Movies;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class DeleteMappedQueryTest extends \pyramid\test\db\query\DeleteQueryAbstract{

		public function getObjQuery(){
			return new DeleteMappedQuery(new Movies);
		}
	}