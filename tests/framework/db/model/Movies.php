<?php

	namespace pyramid\test\db\model;
	use pyramid\db\model\Model;
	/**
	 * Имитация класса пользовательской модели Movies
	 */
	class Movies extends Model{
		public $title;
		public $year;
		public $format;
	}