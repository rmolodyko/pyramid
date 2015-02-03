<?php

	namespace test\framework\orm\query;

	/**
	 * Имитация класса пользовательской модели Movies
	 */
	class Movies extends \framework\orm\query\Model{
		public $title;
		public $year;
		public $format;
	}