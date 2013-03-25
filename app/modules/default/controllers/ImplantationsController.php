<?php

class ImplantationsController extends Zend_Controller_Action
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
        $this->_helper->redirector('cno');
    }
    
    public function cnoAction() {
        $this->view->page = $this->table->getPageByName('cno');
        $this->render('page');
    }
    
    public function bordeauxAction() {
        $this->view->page = $this->table->getPageByName('bordeaux');
        $this->render('page');
    }
    
    public function anversAction() {
        $this->view->page = $this->table->getPageByName('anvers');
        $this->render('page');
    }
    
    public function cognacAction() {
        $this->view->page = $this->table->getPageByName('cognac');
        $this->render('page');
    }
    
    public function gevreyAction() {
        $this->view->page = $this->table->getPageByName('gevrey');
        $this->render('page');
    }
    
    public function fosAction() {
        $this->view->page = $this->table->getPageByName('fos');
        $this->render('page');
    }
    
    public function leHavreAction() {
        $this->view->page = $this->table->getPageByName('le-havre');
        $this->render('page');
    }
    
    public function lyonVenissieuxAction() {
        $this->view->page = $this->table->getPageByName('lyon-venissieux');
        $this->render('page');
    }
    
    public function marseilleAction() {
        $this->view->page = $this->table->getPageByName('marseille');
        $this->render('page');
    }
    
    public function parisValentonAction() {
        $this->view->page = $this->table->getPageByName('paris-valenton');
        $this->render('page');
    }
    
    public function strasbourgAction() {
        $this->view->page = $this->table->getPageByName('strasbourg');
        $this->render('page');
    }
    
    public function toulouseAction() {
        $this->view->page = $this->table->getPageByName('toulouse');
        $this->render('page');
    }
    
}