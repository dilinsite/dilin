<?php

class Admin_ContactController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->contactSelected = true;
    }

    public function indexAction()
    {
        $contact_table = new Model_DbTable_Contact();
        $this->view->messages = $contact_table->getContacts();
    }
}