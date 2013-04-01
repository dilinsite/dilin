<?php

class Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';
	
    protected $_primary = 'id';
	
    public function getCategories()
    {
        $sql = "SELECT * FROM categories WHERE status = '1'";
        return $this->_db->query($sql)->fetchAll();
    }
	
	/**
	 * Recupere tous les enfants par son parent ID
	 *
	 * @return Zend_Db_Table_Rowset
	 */
	public function getCategoryByParentId($id)
	{
		$select = $this->select()->where('parent_id = ?', (int) $id)->where('is_active = ?', 1)->order('sort ASC');
		return $this->fetchAll($select);
	}

	/**
	 * Recupere une categorie par son ID
	 *
	 * @return Zend_Db_Table_Row
	 */
	public function getCategoryById($id)
	{
		$select = $this->select()->where('category_id = ?', (int) $id)->where('is_active = ?', 1)->order('sort ASC');
		return $this->fetchRow($select);
	}
	
	/**
	 * Methode qui permet de check si un row exist
	 * selon un critère (column) avec une valeur donnée (value)
	 *
	 * @return Zend_Db_Table_Row
	 */
	public function recordExists($column, $value)
	{
		$select = $this->select();
		$select->where($column . ' = ?', $value);
	
		return $this->fetchRow($select);
	}
}