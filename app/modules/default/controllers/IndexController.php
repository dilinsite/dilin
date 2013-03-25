<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->viewRenderer->setResponseSegment('index');
    }

    public function indexAction()
    {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('metiers', 'widge');
        $actionStack->actionToStack('work', 'widge');
        
       
        $this->table = new Admin_Model_DbTable_Posts();
        Zend_Layout::getMvcInstance()->assign('newsSet', $this->table->getPublishedPosts());
    }
}