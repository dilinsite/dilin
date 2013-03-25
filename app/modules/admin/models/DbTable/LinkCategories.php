<?php

class Admin_Model_DbTable_LinkCategories extends Zend_Db_Table_Abstract
{
	/**
	 * Nom de la table
	 * @var string
	 */
	protected $_name = 'site_link_category';
	
	/**
	 * Nom de la clef primaire
	 * @var string
	 */
	protected $_primary = 'link_category_id';
	
	
	
	/**
	 * Recupere le nom de la table
	 * 
	 * @return string
	 */
	public function getTableName() {
		return $this->_name;
	}
	
	/**
	 * Get all link categories.
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getLinkCategories() {
		$select = $this->select()->order('name asc');
		return $this->fetchAll($select);
	}
	
	/**
	 * Add a link category
	 *
	 * @param array $data Column-value pairs
	 * @return the job primary id added
	 */
	public function addLinkCategory($data) {
		return $this->insert($data);
	}
}
?>