<?php

class Model_DbTable_Jobs extends Zend_Db_Table_Abstract
{
	/**
	 * table name 
	 * @var string
	 */
	protected $_name = 'site_job';
	
	/**
	 * Name of the primary key
	 * @var string
	 */
	protected $_primary = 'job_id';
	
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