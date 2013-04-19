<?php

class Model_DbTable_Daigous extends Zend_Db_Table_Abstract
{

    protected $_name = 'daigous';
	
    protected $_primary = 'id';
	
    
    public function getLists()
    {
        $sql = "SELECT * FROM " . $this->_name;
        $sql .= " ORDER BY create_ts DESC";
        return $this->_db->query($sql)->fetchAll();
    }
    
    public function getDaigous()
    {
        $sql = "SELECT daigous.*, brands.name as brand_name FROM daigous
                 LEFT JOIN brands ON daigous.brand_id = brands.id 
                 ORDER BY create_ts DESC";
        return $this->_db->query($sql)->fetchAll();
    }
    
    public function addDaigou($data)
    {
        return $data && is_array($data) ? $this->insert($data) : false;
    }
    
    public function getJobs($limit = null)
	{
            $select = $this->select()->where('status=?','enabled')->order('mdate DESC');
            if(is_int($limit)) {
                    $select->limit($limit);
            }

            return $this->fetchAll($select);
	}
	
	public function searchJobs($conditions = array())
	{
		if(empty($conditions))
		{
			return  array();
		}
		else
		{
			$select = $this->select();
			foreach ($conditions as $key=>$value)
			{
				$select->where($key." LIKE (?) ", "%".$value."%");
			}
			
			$result = $this->fetchAll($select);
			if($result)
			{
				return  $result;
			}else
			{
				return array();
			}
		}	
	}
	
	public function showJob($id)
	{
		$select = $this->select()->where('job_id=?',(int)$id);
		$row = $this->fetchRow($select);
		
		
		return $row;
	}
	
	
	
	
	
	
	
}