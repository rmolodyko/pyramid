<?php

	namespace pyramid\db\model;

	/**
	 * Пример пользовательской модели
	 */
	class Movies extends DomainModel{

		public $title;
		public $year;
		public $format;

		/**
		 * @todo Вынести в суперкласс
		 */
		function init(Array $fields = []){
			$this->id = $fields['id'];
			$this->title = $fields['title'];
			$this->year = $fields['year'];
			$this->format = $fields['format'];
		}
	}