<?php

class DaigouController extends Zend_Controller_Action
{
    
    public function init()
    {
        
        $this->_table = new Model_DbTable_ShoppingLists();
        $this->view->controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
    
    public function indexAction()
    {
        
        $lists = $this->_table->getLists();
        $this->view->lists = $lists;
                
    }
}

?>
