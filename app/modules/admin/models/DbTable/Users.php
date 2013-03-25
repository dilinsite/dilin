<?php

class Admin_Model_DbTable_Users extends Zend_Db_Table_Abstract {
	/**
	 * Table name
	 * 
	 * @var string
	 */
	protected $_name = 'site_user';
	
	/**
	 * Primary ID
	 * 
	 * @var string
	 */
	protected $_primary = 'user_id';
	
	/**
	 * Username
	 * 
	 * @var string
	 */
	protected $_username = 'username';
	
	/**
	 * Password
	 * 
	 * @var string
	 */
	protected $_password = 'password';
	
	/**
	 * Get table name
	 * 
	 * @return string
	 */
	public function getTableName() {
		return $this->_name;
	}
	
	/**
	 * Get primary key.
	 *
	 * @return string
	 */
	public function getPrimaryKey() {
		//$cols = $this->info('cols');
		//return $cols[0];
		return $this->_primary;
	}
	
	/**
	 * Retrieve the name of the identity column
	 * 
	 * @return string
	 */
	public function getIdentityColumn() {
		return $this->_username;
	}
	
	/**
	 * Retrieve the column name credential
	 * 
	 * @return string
	 */
	public function getCredentialColumn() {
		return $this->_password;
	}
	
	/** 
	 * Get user by username
	 * 
	 * @param string $username
	 * 
	 */
	public function getUserByUsername($username) {
		return $this->fetchRow('username = ' . $username);
	}
	
	
	/**
	 * Get user.
	 * 
	 * @param array $where
	 */
	public function getUserBy($where = array()) {
		$select = $this->select();
		if (count($where) > 0) {
			foreach ($where as $key=>$value)
				$select->where($key . ' = ?', $value);
		}
  		
		return $this->fetchRow($select);
	}
	
	/**
	 * Get user by id.
	 * 
	 * @param int $id
	 * @return the user row or null 
	 */
	public function getUser($id) {
		return $this->fetchRow('user_id = ' . (int)$id);
	}

	/**
	 * Update a user profile
	 * 
	 * @param int $id
	 * @param array $data 
	 * 
	 * @return 1 if sucessful otherwise 0
	 */
	public function editUser($id, $data) {
		if ($data) {
			$where = $this->getAdapter()->quoteInto('user_id = ?', (int)$id);
			return $this->update($data, $where);
		}
		return false;
	}
}