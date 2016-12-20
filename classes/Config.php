<?php
	class Config {
		/**
		 *@author TanNM
		 *since 1.0
		*/


		/**
			 *lớp get
			 *
			 *@param string $path giá trị của từng phần tử mảng $path
			 *@return string|false trả về giá trị $config hoặc false	
			*/
		public static function get($path = null) {
			
			if ($path) {
				$config = $GLOBALS['config'];
				$path	= explode('/', $path);

				foreach ($path as $bit) {
					if (isset($config[$bit])) {
						$config = $config[$bit];
					}
				}

				return $config;
			}
			
			return false;
		}
	}
?>