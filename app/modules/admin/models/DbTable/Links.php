<?php

class Admin_Model_DbTable_Links extends Zend_Db_Table_Abstract {
	
	/**
	 * Nom de la table
	 * @var string
	 */
	protected $_name = 'site_link';
	
	/**
	 * Nom de la clef primaire
	 * @var string
	 */
	protected $_primary = 'link_id';
	
	
}