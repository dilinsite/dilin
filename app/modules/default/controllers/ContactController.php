<?php

class ContactController extends Zend_Controller_Action
{
    public function init() {
        /*$actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('transport', 'widge');
        $actionStack->actionToStack('work', 'widge');
        $this->table = new Admin_Model_DbTable_Pages();\
         * 
         */
        $this->view->contactSelected = true;
    }
    
    public function indexAction() {
       // $this->_helper->redirector('siege');
    }
    
    public function siegeAction() {
        $this->view->page = $this->table->getPageByNameAndParentId('siege', 8);
        $this->view->bg   = 'siege';
        $this->render('page');
    }
    
    public function centreDeNationalDesOperationsAction() {
        $this->view->page = $this->table->getPageByNameAndParentId('centre-de-national-des-operations', 8);
        $this->view->bg   = 'index';
        $this->render('page');
    }
    
    public function directionCommercialeAction() {
        $this->view->page = $this->table->getPageByNameAndParentId('direction-commerciale', 8);
        $this->view->bg   = 'dirco';
        $this->render('page');
    }
    
    public function navitruckingAction() {
        $this->view->page = $this->table->getPageByNameAndParentId('navitrucking', 8);
        $this->view->bg   = 'navitrucking';
        $this->render('page');
    }
}