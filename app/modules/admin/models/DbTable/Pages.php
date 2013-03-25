<?php

class Admin_Model_DbTable_Pages extends Zend_Db_Table_Abstract {
    
    /** table name */
    protected $_name = 'site_page';
	
    /** Name of the primary key */
    protected $_primary = 'page_id';
    protected $_id = 'page_id';

    public function getTableName() {
        return $this->_name;
    }

    public function getPages() {
        $select = $this->select()->where('parent_id = ? ', 0);
        return $this->fetchAll($select);
    }
    
    public function getPagesByParentId($id) {
        $select = 'SELECT site_page_1.*, site_page_2.name AS parent_name '
                . 'FROM site_page AS site_page_1 ' 
                . 'LEFT JOIN site_page AS site_page_2 '
                . 'ON site_page_1.parent_id = site_page_2.page_id '
                . 'WHERE site_page_1.parent_id = ' . $id;
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function getPage($id, $join_parent = false) {
        if ($join_parent) {
            $select = 'SELECT site_page_1.*, site_page_2.name AS parent_name ' 
                    . 'FROM site_page AS site_page_1 ' 
                    . 'LEFT JOIN site_page AS site_page_2 ' 
                    . 'ON site_page_1.parent_id = site_page_2.page_id ' 
                    . 'WHERE site_page_1.page_id = ' . $id;
            return $this->getAdapter()->fetchAll($select);
        } else {
            $select = $this->select()->where('page_id = ?', $id);
            return $this->fetchRow($select);
        }
    }
    
    public function getPageByName($name) {
        $select = $this->select()->where('name = ?', $name)->where('parent_id > ?', 0);
        return $this->fetchRow($select);
    }
    
    public function getPageByNameAndParentId($name, $parent_id) {
        $select = $this->select()
                ->where('name = ?', $name)
                ->where('parent_id = ?', $parent_id);
        return $this->fetchRow($select);
    }
    
    public function getParentId($id) {
        $select = $this->select()
                ->from('site_page', array('parent_id'))
                ->where('page_id = ?', $id);
        return $this->fetchRow($select);
    }

    public function updatePage($data, $id) {
        $where = $this->_id . '=' . $id;
        return $this->update($data, $where);
    }
}    
?>
