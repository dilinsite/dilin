<?php

class Admin_DashboardController extends Zend_Controller_Action
{
	
    public function init()
    {
        $this->view->dashboardSelected = true;
    }

    public function indexAction()
    {
        
        $category_form = new Admin_Form_Category();

        $categories_table = new Model_DbTable_Categories();
        
        $this->view->category_form = $category_form;
        $this->view->categories = $categories_table->getCategories();
        
        
    }

    public function setcookieAction() 
    {
        $lang = $this->_request->getParam('c');

        if ($lang == 'en') {
            setcookie('locale', 'en_US', time() + 3600, '/');
        } else if ($lang == 'fr') {
            setcookie('locale', 'fr_FR', time() + 3600, '/');
        } 

        $this->_helper->redirector();
    }
}