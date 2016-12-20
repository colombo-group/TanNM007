<?php
/**
 *@author abc
 *since 1.0	
*/
	class Database {
		/**
		 ** lớp database
		 *@param object $_instance  đối tượng Database
		 *@param object $_pdo  đối tượng PDO
		 *@param object $_query 	đối tượng PDO
		 *@param object $_error 	đối tượng PDO_error
		 *@param array $_results mảng kết quả câu truy vấn
		 *@param int $_count số lượng num_row
		*/
		private static $_instance = null;
		private $_pdo,
				$_query,
				$_error = false,
				$_results,
				$_count = 0;

		/**
		 **hàm khởi tạo đối tượng PDO
	     */
		private function __construct() {
			
			try {
				$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}

		/**
         **hàm tạo đối tượng database mới
         * @return object database
	     */
		public static function getInstance() {
			
			if (!isset(self::$_instance)) {
				self::$_instance = new Database();
			}
			return self::$_instance;
		}
			/**
             **hàm thực thi câu truy vấn
             *@param string $sql câu truy vấn sql
             *@param array tham số truyền vào bind_param
             *@retunr trả về dối tượng database
			*/
		public function query($sql, $params = array()) {
			
			$this->_error = false;
			if ($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if (count($params)) {
					foreach ($params as $param) {
						$this->_query->bindValue($x, $param);
						$x++;
					}
				}

				if ($this->_query->execute()) {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count	= $this->_query->rowCount();
				} else {
					$this->_error = true;
				}
			}

			return $this;
		}
			/**
			 **hàm thực thi câu truy vấn có điều kiện
             *@param string $action  thực thi cái gì
             *@param string $table bảng nào
             *@param array $where mảng chứa điều kiênk
             *@retunr trả về giá trị
			*/
		public function action($action, $table, $where = array()) {
			
			if (count($where) === 3) {	//Allow for no where
				$operators = array('=','>','<','>=','<=','<>');

				$field		= $where[0];
				$operator	= $where[1];
				$value		= $where[2];

				if (in_array($operator, $operators)) {
					$sql = "{$action} FROM {$table} WHERE ${field} {$operator} ?";
					if (!$this->query($sql, array($value))->error()) {
						return $this;
					}
				}
			}
			return false;
		}
		/**
			 **hàm thực thi câu truy vấn có điều kiện lấy ra tất cả
             *@param string $table bảng nào
             *@param array $where mảng chứa điều kiênk
             *@retunr trả về giá trị
			*/
		public function get($table, $where) {
			
			return $this->action('SELECT *', $table, $where); //ToDo: Allow for specific SELECT (SELECT username)
		}
		/**
			 **hàm thực thi xoas
             *@param string $table bảng nào
             *@param array $where mảng chứa điều kiênk
             *@return trả về giá trị
			*/
		public function delete($table, $where) {
			
			return $this->action('DELETE', $table, $where);
		}
		/**
			 **hàm thực thi câu truy vấn thêm mói
             *@param string $table bảng nào
             *@param array $fields mảng value
             *@return true|fasle  
			*/
		public function insert($table, $fields = array()) {
			
			if (count($fields)) {
				$keys 	= array_keys($fields);
				$values = null;
				$x 		= 1;

				foreach ($fields as $field) {
					$values .= '?';
					if ($x<count($fields)) {
						$values .= ', ';
					}
					$x++;
				}

				$sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES({$values})";

				if (!$this->query($sql, $fields)->error()) {
					return true;
				}
			}
			return false;
		}
		/**
			 **hàm thực thi câu truy vấn cập nhật
             *@param string $table bảng nào
             *@param int $id id
             *@param array $fields mảng giá trị cập nhật
             *@return true|fasle  
			*/
		public function update($table, $id, $fields = array()) {
			
			$set 	= '';
			$x		= 1;

			foreach ($fields as $name => $value) {
				$set .= "{$name} = ?";
				if ($x<count($fields)) {
					$set .= ', ';
				}
				$x++;
			}

			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
			
			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
			return false;
		}
		/**
		 **hàm trả về giá tri
		*/
		public function results() {
			
			return $this->_results;
			}
			/**
			 **hàm trả về giá tri đầu tiên trong mảng réult
			*/
		public function first() {
			
			return $this->_results[0];
		}
		/**
			 **hàm trả về giá tri object lỗi
			*/
		public function error() {
			
			return $this->_error;
		}
		/**
			 **hàm trả về giá tri sl
			*/
		public function count() {
			
			return $this->_count;
		}
	}
?>