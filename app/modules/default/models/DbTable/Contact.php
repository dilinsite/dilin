<?php

class Model_DbTable_Contact extends Zend_Db_Table_Abstract
{

    protected $_name = 'contact';
	
    protected $_primary = 'id';

    
    public function getContacts()
    {
        $sql = "SELECT * FROM contact ORDER BY create_ts DESC";
        return $this->_db->query($sql)->fetchAll();
    }
    
    
    public function getContactById($id)
    {
        $sql = "SELECT * FROM contact WHERE id = $id";
        return $this->_db->query($sql)->fetch();
    }
    
    /**
     * Add a contact
     * 
     * @param array $data Column-value pairs
     * @return the primary contact id added
     */
    public function addContact($data) 
    {
        return $data && is_array($data) ? $this->insert($data) : false;
    }
    
}
