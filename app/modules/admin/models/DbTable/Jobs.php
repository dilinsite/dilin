<?php

class Admin_Model_DbTable_Jobs extends Zend_Db_Table_Abstract 
{

    protected $_name = 'site_job';
    protected $_primary = 'job_id';
	
    /**
     * 
     * @param array $conditions
     */
    public function searchJobs($conditions = array()) 
    {
        $select = $this->select();
        foreach ($conditions as $k => $v)
            $select->where($k . ' LIKE ? ', '%'. $v .'%');

        return $this->fetchAll($select);
    }

    /**
     * Get a job
     *
     * @param int $id
     *
     */
    public function getJob($where) 
    {
        if (is_numeric($where)) {
            return $this->fetchRow('job_id = ' . $where);
        }
        if (is_string($where)) {
            return $this->fetchRow($where);
        }
        return null;
    }


    /**
     * Retrieve jobs
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getJobs($join = null, $where = null) 
    {
        if ($join != null && $join == 'site_job_category') {
            $select = $this->select()->setIntegrityCheck(false);
            $select->from('site_job','*');
            $select->where('site_job.status!="deleted"');
            $select->join('site_job_category','site_job.job_category_id=site_job_category.job_category_id', 'name as job_category_name');
            $select->order('site_job_category.name asc');
            $select->order('site_job.title asc');
            $select->order('site_job.cdate DESC');
        } else {
            $select = $this->select()->from('site_job', '*')->where('status!=?','deleted');
        }
        
        if ($where) {
            if (is_array($where)) {
                foreach ($where as $k => $v) 
                    $select->where('site_job.' . $k . '= ?', $v);
            } 
        }
        
        return $this->fetchAll($select);
    }
    
    public function getEnabledJobs() {
        $select = $this->select()->where('status = ?', 'enabled')->order('cdate DESC');
     
        if (isset($_COOKIE['locale']) && $_COOKIE['locale'] == 'en_US')          
            $select->where ('language = ?', 'en');
        else 
            $select->where('language = ?', 'fr');
        
        return $this->fetchAll($select);
    }
	

    /**
     * Add a job
     * 
     * @param array $data Column-value pairs
     * @return the job primary id added
     */
    public function addJob($data) 
    {
        return $data && is_array($data) ? $this->insert($data) : false;
    }

    /**
     * Update a job
     * 
     * @param int $id
     * @param array $data
     */
    public function updateJob($data, $where) 
    {
        if ($data && $where) {
            if (is_numeric($where)) {
                return $this->update($data, 'job_id = ' . $where);
            }
        }
        return false;
    }

    /**
     * Delete a job.  
     * 
     * @param unknown_type $id
     */
    public function deleteJob($id) 
    {
        $status = array('status' => 'deleted');
        
        if (is_numeric($id)) {
            return $this->update($status, "job_id = " . (int)$id);
        }

        return null;
    }
}