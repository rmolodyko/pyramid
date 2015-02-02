<?php

	require_once(dirname(__FILE__).'/../../../lib/include.php');

	class UpdateNativeQueryTest extends UpdateQueryAbstract{

		public function getObjQuery(){
			return new UpdateNativeQuery('movies');
		}
	}