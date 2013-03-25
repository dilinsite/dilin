<?php

class Model_DbTable_Categories extends Zend_Db_Table_Abstract
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
	 * Recupere toutes les categories
	 * 
	 * @return Zend_Db_Table_Rowset
	 */
	public function getCategories()
	{
		$select = $this->select()->where('is_active = ?', 1)->order('sort ASC');
		return $this->fetchAll($select);
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