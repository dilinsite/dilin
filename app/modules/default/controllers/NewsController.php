<?php

class NewsController extends Zend_Controller_Action
{

    public function init() {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('transport', 'widge');
        $actionStack->actionToStack('work', 'widge');
        $this->table = new Admin_Model_DbTable_Posts();
    }

    public function indexAction() {
        $this->view->focusRowset = $this->table->getPublishedPosts();
        $this->view->filename = 'crop_home.jpg';
    }
    
    public function viewAction() {
        $id = $this->getRequest()->getParam('id');
        $news = $this->table->getPost($id); 
        $coords = Zend_Json::decode($news->crop_coords);
        $this->view->filename = 'crop_' . $coords['filename'];
        $this->view->news = $news;
    }
}
