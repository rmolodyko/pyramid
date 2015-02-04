<?php

	namespace pyramid\test\db\query\native;
	use \pyramid\db\query\native\UpdateNativeQuery;

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class UpdateNativeQueryTest extends \pyramid\test\db\query\UpdateQueryAbstract{

		public function getObjQuery(){
			return new UpdateNativeQuery('movies');
		}
	} 