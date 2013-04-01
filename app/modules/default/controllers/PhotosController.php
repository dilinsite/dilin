<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhotosController
 *
 * @author dilin
 */
class PhotosController extends Zend_Controller_Action
{

    public function init()
    {
        
        $this->view->photosSelected = true;
        
    }

    public function indexAction()
    {
        
        // list albums
        $albums_table = new Model_DbTable_Albums();
        $this->view->albums = $albums_table->getAlbums();
        
        /*
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('metiers', 'widge');
        $actionStack->actionToStack('work', 'widge');
        
       
        $this->table = new Admin_Model_DbTable_Posts();
        Zend_Layout::getMvcInstance()->assign('newsSet', $this->table->getPublishedPosts());
         * 
         */
    }
}