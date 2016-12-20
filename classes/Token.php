<?php

	class Token {
		/**
         *@autor TanNM
         ** lớp làm việc token
		*/
		public static function generate() {
			/**
             **hàm tạo mã
             *@return session token
			*/
			return Session::put(Config::get('session/tokenName'), md5(uniqid()));
		}

		public static function check($token) {
			/**
             **kiểm tra mã
             *@param string $token tên token 
             *@return true false
			*/
			$tokenName = Config::get('session/tokenName');

			if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
				Session::delete($tokenName);
				return true;
			} else {
				return false;
			}
		}
	}
?>