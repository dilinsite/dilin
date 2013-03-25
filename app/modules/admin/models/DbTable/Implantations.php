<?php

class Admin_Model_DbTable_Implantations extends Zend_Db_Table_Abstract 
{

    protected $_name = 'site_implantation';
    protected $_primary = 'implantation_id';
	
    public function getImplantation($where) {
        if (is_numeric($where)) {
            return $this->fetchRow('implantation_id = ' . $where);
        }
        if (is_string($where)) {
            return $this->fetchRow($where);
        }
        
        return null;
    }
    
    public function getImplantationByName($name) {
        return $this->fetchRow($this->select()->where('name = ?', $name));  
    }

    public function getImplantations() {
        return $this->fetchAll();
    }
    
    public function updateImplantation($data, $id) {
        if ($data && $id && is_numeric($id)) 
            return $this->update($data, 'implantation_id = ' . $id);

        return false;
    }
}