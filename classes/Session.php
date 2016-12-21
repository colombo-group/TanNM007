<?php
		/**
         *@author TanNM
         *lớp làm việc với session
		 */
	class Session {
			/**
             * kiểm tra seesion $name tồn tại k
             *@param $name tên seesion
             *@return true nếu tồn tại.
			 */
		public static function exists($name) {
			
			return (isset($_SESSION[$name])) ? true : false;
		}
			/**
             * gán giá trị session
             *@param string $name tên session
             *@param string $value giá trị session
             *@return session
			 */
		public static function put($name, $value) {
			
			return $_SESSION[$name] = $value;
		}
			/**
             *lấy giá trị session
             *@param string $name tên session
             *@return session
			 */
		public static function get($name) {
			
			return $_SESSION[$name];
		}
			/**
             *xóa session
             *@param string $name tên session
			 */
		public static function delete($name) {
           
			if (self::exists($name)) {
				unset($_SESSION[$name]);
			}
		}
			/**
             *tạo session flash
             *@param string $name tên session
             *@param string $string value session
             *@return session
			 */
		public static function flash($name, $string = '') {
			
			if (self::exists($name)) {
				$session = self::get($name);
				self::delete($name);
				return $session;
			} else {
				self::put($name, $string);
			}
		}
	}	
?>