<?php

class Model_DbTable_Brands extends Zend_Db_Table_Abstract
{

    protected $_name = 'brands';
	
    protected $_primary = 'id';
	
    
    public function getBrands()
    {
        $sql = "SELECT * FROM " . $this->_name;
        return $this->_db->query($sql)->fetchAll();
    }
    
}