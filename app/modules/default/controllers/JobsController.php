<?php

class JobsController extends Zend_Controller_Action
{
    public function init() {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('transport', 'widge');
        $actionStack->actionToStack('work', 'widge');
        $this->table = new Admin_Model_DbTable_Pages();
    }
	
    public function indexAction() {
        $this->_helper->redirector('politique-rh');
    }
	
    public function politiqueRhAction() {
        $this->view->page = $this->table->getPageByName('politique-rh');
        $this->render('page');
    }
    
    public function offersAction() {
        $table = new Admin_Model_DbTable_Jobs();
        $this->view->focusRowset = $table->getEnabledJobs();
    }
}