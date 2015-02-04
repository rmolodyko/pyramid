<?php

	namespace pyramid\test\db\query\native;
	use \pyramid\db\query\native\DeleteNativeQuery;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class DeleteNativeQueryTest extends \pyramid\test\db\query\DeleteQueryAbstract{

		public function getObjQuery(){
			return new DeleteNativeQuery('movies');
		}

	}