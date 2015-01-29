<?php

	namespace Test;

	require_once("lib/Model.php");
	require_once("helper/TestHelper.php");

	use PHPUnit_Framework_TestCase;
	use App\Model;
	use Test\Helper\TestHelper;

	class UserStoreTest extends PHPUnit_Framework_TestCase{

		public function setUp(){
			TestHelper::deleteData();
			TestHelper::createData();
		}

		public function tearDown(){}

		public function testFindAll(){
			$this->assertEquals((new Model)->findAll(),TestHelper::findAll());
		}
	}