<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        //$this->_helper->layout()->disableLayout();
	//$this->_helper->viewRenderer->setNoRender(true);
       // $this->_helper->viewRenderer->setResponseSegment('index');
        $this->view->indexSelected = true;
    }

    public function indexAction()
    {
        /*
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('metiers', 'widge');
        $actionStack->actionToStack('work', 'widge');
        
       
        $this->table = new Admin_Model_DbTable_Posts();
        Zend_Layout::getMvcInstance()->assign('newsSet', $this->table->getPublishedPosts());
         * 
         */
    }
}