<?php

class Admin_Model_DbTable_JobCategories extends Zend_Db_Table_Abstract
{
    
    const NAME = 'site_job_category';
    
    /**
     * Nom de la table
     * @var string
     */
    protected $_name = self::NAME;

    /**
     * Nom de la clef primaire
     * @var string
     */
    protected $_primary = 'job_category_id';


    protected $_title = 'title';

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
     * Get Job type row by primary key. 
     * 
     * @param int $id
     * @return Zend_Db_Table_Abstract
     */
    public function getJobCategory($id) 
    {        
        if (is_numeric($id)) {
            return $this->find($id)->current();
        }
        return null;
    }
    
    
    /**
     * Get job type rows.
     * 
     * @return Zend_Db_Table_Rowset_Abstract 
     */
    public function getJobCategories($order = null) 
    {
        $select = $this->select();
        
        if ($order) {
            $select->order($order);
        } else {
            $select->order('name asc');
        }
        
        return $this->fetchAll($select);
    }

    /**
     * Get job type titles.
     * 
     * @return array
     */
    public function getJobCategoryTitles() {
            $rows = $this->fetchAll();
            $titles = array();
            foreach ($rows as $row)
                    $titles[$row->job_category_id] = $row->name;
            return $titles;
    }


    /**
     * Add a new job category.
     * 
     * @param string name
     */
    public function addJobCategory($data) 
    {
        if (is_string($data)) {
            $row = $this->createRow();
            $row->name = $data;
            $row->save();
            return $row->job_category_id;
        } 
        
        if (is_array($data)) {
            return $this->insert($data);
        }
        
        return 0;
    }

    /**
     * Update a job type.
     * 
     * @param array $data
     * @param int $id
     */
    public function updateJobCategory($data, $where) 
    {
        if ($data && $where) {
            if (is_numeric($where)) {
                return $this->update($data, 'job_category_id = ' . $where);
            }
        }
        return 0;
    }
    
    public function deleteJobCategory($where) {
        if ($where == null)
            return 0;
        
        if (is_numeric($where)) {
            return $this->delete('job_category_id = ' . $where);
        }
        if (is_string($where)) {
            return $this->delete($where);
        }
        return 0;
    }
}