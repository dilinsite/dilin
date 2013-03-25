<?php

class Admin_DashboardController extends Zend_Controller_Action
{
	
    public function init()
    {
        Zend_Layout::getMvcInstance()->assign('selectedDashboard', true);
    }

    public function indexAction()
    {
        $jobs_table = new Admin_Model_DbTable_Jobs();
        $this->view->jobs_table = $jobs_table->getJobs();
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