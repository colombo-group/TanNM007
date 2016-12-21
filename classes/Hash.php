<?php
		/**
         *@author TAnNM
         *lóp làm việc với password
		*/
	class Hash {
		
		
		/**
			 *mã hóa mật khẩu
			 *@param string $string mật khẩu chưa mã hóa
			 *@param string $salt muối
			 *@return chỗi mk đã mã hóa
			*/
		public static function make($string, $salt = '') {
			
			return hash('sha256', $string.$salt);
		}
			/**
			 *tạo chuỗi bất kỳ
			 *@param string $lenght độ dài
			 *@return chỗi 
			*/
		public static function salt($length) {
			
			return mcrypt_create_iv($length);
		}
			/**
			 *hàm trả về chuỗi bất kỳ
			 *@return chỗi 
			*/
		public static function unique() {
			
			return self::make(uniqid());
		}
	}
?>