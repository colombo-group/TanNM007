<?php
	class User {
		/**
 		 *@author TanNM
 		 * làm việc với user
 		 *@var $_db object database
 		 *@var $_data mảng dữ liệu
 		 *@var $_sessionName session
 		 *@var $_cookieName cookie
 		 *@var $_isLoggedIn boolean
		*/
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;
			/**
             *hàm khởi tạo
             *@param string $user lấy giá trị session
			*/
		public function __construct($user = null) {
			
			$this->_db 			= Database::getInstance();
			$this->_sessionName = Config::get('session/sessionName');
			$this->_cookieName 	= Config::get('remember/cookieName');

			if (!$user) {
				if (Session::exists($this->_sessionName)) {
					$user = Session::get($this->_sessionName);

					if ($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
						self::logout();
					}
				}
			} else {
				$this->find($user);
			}
		}
		/**
		 *hàm update
		 *@param array $fields
		 *@param int $id
		*/
		public function update($fields = array(), $id = null) {
			
			if (!$id && $this->isLoggedIn()) {
				$id = $this->data()->ID;
			}

			if (!$this->_db->update('users', $id, $fields)) {
				throw new Exception("There was a problem updating your details");
			}
		}

		/**
		 * hàm thêm mới user
		 *
		 * @param    array $fields  Thông tin user
		 *
		 * @throws  Exception  (description)
		 */
		public function create($fields = array()) {
			
			if (!$this->_db->insert('users', $fields)) {
				throw new Exception("There was a problem creating your account");
			}
		}
		/**
		 * hàm tìm kiếm user
		 *
		 * @param    string $user   user
		 *
		 * @return true false
		 */
		public function find($user = null) {
			if ($user) {
				$fields = (is_numeric($user)) ? 'id' : 'username';	//Numbers in username issues
				$data 	= $this->_db->get('users', array($fields, '=', $user));

				if ($data->count()) {
					$this->_data = $data->first();
					return true;
				}
			}
			return false;
		}

		/**
		 * hàm đăng nhập
		 *@param string $username tên 
		 *@param string $password ps 
		 *@param boolean $remember 
		 *	
		 *@return true false	
		 */
		public function login($username = null, $password = null, $remember = false) {
			if (!$username && !$password && $this->exists()) {
				Session::put($this->_sessionName, $this->data()->ID);
			} else {
				$user = $this->find($username);
				if ($user) {
					if ($this->data()->password === Hash::make($password,$this->data()->salt)) {
						Session::put($this->_sessionName, $this->data()->ID);

						if ($remember) {
							$hash = Hash::unique();
							$hashCheck = $this->_db->get('usersSessions', array('userID','=',$this->data()->ID));

							if (!$hashCheck->count()) {
								$this->_db->insert('usersSessions', array(
									'userID' 	=> $this->data()->ID,
									'hash' 		=> $hash
								));
							} else {
								$hash = $hashCheck->first()->hash;
							}
							Cookie::put($this->_cookieName, $hash, Config::get('remember/cookieExpiry'));
						}

						return true;
					}
				}
			}
			return false;
		}

		/**
		 * hàm kiểm tra quyền user
		 *@param string $key mã  
		 *	
		 *@return true false	
		 */
		public function hasPermission($key) {
			$group = $this->_db->get('groups', array('ID', '=', $this->data()->userGroup));
			if ($group->count()) {
				$permissions = json_decode($group->first()->permissions,true);

				if ($permissions[$key] == true) {
					return true;
				}
			}
			return false;
		}
		/**
		 * hàm kiểm tra user tồn tại k
		 *	
		 *@return true false	
		 */
		public function exists() {
			return (!empty($this->_data)) ? true : false;
		}

		/**
		 * hàm đăng xuât
		 *	
		 *	
		 */
		public function logout() {
			$this->_db->delete('usersSessions', array('userID', '=', $this->data()->ID));
			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
		}

		/**
		 * hàm lấy dữ liệu
		 *	
		 *@return data	
		 */
		public function data() {
			return $this->_data;
		}

		/**
		 * hàm kiểm tra đăng nhập chưa
		 *	
		 *@return boolean _isloggedIn	
		 */
		public function isLoggedIn() {
			return $this->_isLoggedIn;
		}
	}
?>