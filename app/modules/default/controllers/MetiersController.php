<?php

class MetiersController extends Zend_Controller_Action
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
        $this->_helper->redirector('film-animation');
    }
    
    public function filmAnimationAction() {

    }
    
    public function operatorAction() {
        $this->view->page = $this->table->getPageByName('operator');
        $this->render('page');
    }
    
    public function terminalAction() {
        $this->view->page = $this->table->getPageByName('terminal');
        $this->render('page');
    }
    
    public function tractionAction() {
        $this->view->page = $this->table->getPageByName('traction');
        $this->render('page');
        //Zend_Layout::getMvcInstance()->assign('page_id', 'metiers_traction');
    }
}