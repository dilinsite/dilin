<?php

class Model_DbTable_Quote extends Zend_Db_Table_Abstract
{
    protected $_name = 'site_quote';

    protected $_primary = 'quote_id';
	
    public function addQuote($data) 
    {
        return $data && is_array($data) ? $this->insert($data) : false;
    }
}