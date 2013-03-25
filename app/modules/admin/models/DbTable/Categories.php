<?php

class Admin_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{
	/**
	 * Nom de la table
	 * @var string
	 */
	protected $_name = 'site_category';
	
	/**
	 * Nom de la clef primaire
	 * @var string
	 */
	protected $_primary = 'category_id';
	
	/**
	 * Recupere le nom de la table
	 * 
	 * @return string
	 */
	public function getTableName()
	{
		return $this->_name;
	}
	
	/**
	 * Recupere le nom de la clef primaire
	 *
	 * @return string
	 */
	public function getPrimaryKey()
	{
		$cols = $this->info('cols');
		return $cols[0];
	}
	
	/**
	 * Get categories.
	 * 
	 * @param array $cond
	 * @param mixed $order
	 * @param int $limit
	 * 
	 * @return Zend_Db_Table_Rowset_Abstract 
	 */
	public function getCategories($cond = array(),$order = null,$limit = null) {
		$select = $this->select();
		
		if ($cond)
			foreach ($cond as $key=>$value)
				$select->where($key."=?",$value);
		
		if($order)
			$select->order($order);
		
		if($limit)
			$select->limit($limit);
		
		return $this->fetchAll($select);
	}
	
	/**
	 * Get category by primary key. 
	 * 
	 * @param int $id
	 * @return Zend_Db_Table_Abstract
	 */
	public function getCategory($id) {
		return $this->find($id)->current();
	}
	
	/**
	 * Add a new category.
	 * 
	 * @param array $data
	 * @return the newly added category id or null
	 */
	public function addCategory($data = null) {
		$row = $this->createRow();
		if($data) {
			foreach ($data as $key=>$value) {
				$row->$key = $value;
			}
			$row->save();
			return $row->category_id;
		}
		return 0;
	}
	
	/**
	 * Delete a category by disabling its status.
	 * 
	 * @param int $id
	 * @return Zend_Db_Table_Abstract
	 */
	public function deleteCategory($id) {
		return $this->update(array('status'=>'disabled'), 'category_id='.(int)$id);
	}
	
	/**
	 * Edit a category.
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $id
	 */
	public function editCategory($data, $id) {
		if($data)
			return $this->update($data,'category_id='.(int)$id);
		return 0;
	}
}