<?php


class OthersController extends Zend_Controller_Action
{

    public function init()
    {
        //$this->_helper->layout()->disableLayout();
	//$this->_helper->viewRenderer->setNoRender(true);
        //$this->_helper->viewRenderer->setResponseSegment('index');
        $this->view->othersSelected = true;
    }

    public function indexAction()
    {
        $daigous_table = new Model_DbTable_Daigous();
        $this->view->daigous = $daigous_table->getDaigous();
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