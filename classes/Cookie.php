<?php
	class Cookie {
		/**
	     *@author TanNM
		 ** lớp làm việc với cookie
		*/

		/**
			 **hàm  kiểm xem cookie có tồn tại
			 *@param string $_COOKIE['$name'] value của cookie $name
			 *@return true|false nếu tồn tại trả về true|false  
			*/
		public static function exists($name) {
			
			return (isset($_COOKIE[$name])) ? true : false;
		}
/**
			 **hàm  lấy giá trị cookie có tên $name
			 *@param string $name tên  cookie cần lấy
			 *@return tên cookie $name  
			*/
		public static function get($name) {
			
			return $_COOKIE[$name];
		}
/**
			 **hàm  tạo cookie 
			 *@param string $name tên cookie 
			 *@param string $value giá trị cookie 
			 *@param int $expiry thời gian sống cookie 
			 *@return true|false nếu tạo thành công  
			*/
		public static function put($name, $value, $expiry) {
			
			if (setcookie($name, $value, time()+$expiry, '/')) {
				return true;
			}
			return false;
		}
/**
			 **hàm  xóa cookie 
			 *@param string $name tên của cookie $
			*/
		public static function delete($name) {
			
			self::put($name, '', time()-1);
		}
	}
?>