<?php

class ShoppingListsController extends Zend_Controller_Action
{
    
    public function init()
    {
        
        $this->_table = new Model_DbTable_ShoppingLists();
        
    }
    
    public function indexAction()
    {
        
        $lists = $this->_table->getLists();
        $this->view->lists = $lists;
                
    }
}

?>
