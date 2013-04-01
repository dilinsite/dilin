<?php

class Model_DbTable_Photos extends Zend_Db_Table_Abstract
{

    protected $_name = 'photos';
        
    protected $_primary = 'id';
    
	
    public function getPhotos($album_id = null) 
    {
        
        if ($album_id) {
            $sql = "SELECT * FROM photos WHERE album_id = $album_id";
        } else {
            $sql = "SELECT * FROM photos";
        }
        
        return $this->_db->query($sql)->fetchAll();
        
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