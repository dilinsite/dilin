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
class Admin_GalleriesController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->galleriesSelected = true;
    }

    public function indexAction()
    {
        
        // list albums
        $albums_table = new Model_DbTable_Albums();
        $this->view->albums = $albums_table->getAlbums();
        
    }
}