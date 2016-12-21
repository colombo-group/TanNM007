<?php
		/**
		 *@author TanNM
		 **Lớp kiểm tra input
		*/

		/**
			 *hàm kiểm tra phuoeng thức post hoặc get
			 *@param string $type tên phuong thức
			 *@return true false
			*/
	class Input {
		
		
			
		public static function exists($type = 'post') {
			
			switch ($type) {
				case 'post':
					return (!empty($_POST)) ? true : false;
					break;
				case 'get':
					return (!empty($_GET)) ? true : false;
					break;
				default:
					return false;
					break;
			}
		}
			/**
			 *hàm kiểm tra tồn tại biến k
			 *@param string $item tên biến
			 *@return true false
			*/
		public static function get($item) {
			
			if (isset($_POST[$item])) {
				return $_POST[$item];
			} else if (isset($_GET[$item])) {
				return $_GET[$item];
			}
			return '';
		}
	}
?>