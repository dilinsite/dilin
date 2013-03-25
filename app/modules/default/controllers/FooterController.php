<?php

class FooterController extends Zend_Controller_Action
{
    public function init() {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('transport', 'widge');
        $actionStack->actionToStack('work', 'widge');
        $this->table = new Admin_Model_DbTable_Pages();
       //$this->_helper->getHelper('layout')->disableLayout();
    }
	
    public function indexAction() {
        $this->_helper->redirector('credits');
        //$this->_helper->viewRenderer->setNoRender();
    }
	
    public function creditsAction() {
        $this->view->page = $this->table->getPageByNameAndParentId('credits', 41);
        $this->render('page');
    }
    
    public function sitemapAction() {
        //$this->view->page = $this->table->getPageByNameAndParentId('sitemap', 41);
        //$this->render('page');
    }
    
    public function legalTermsAction() {
        $this->view->page = $this->table->getPageByNameAndParentId('legal-terms', '41');
        $this->render('page');
    }
}