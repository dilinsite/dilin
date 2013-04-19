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
class Admin_OthersController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->othersSelected = true;
    }

    public function indexAction()
    {
        
        $this->view->daigou_form = new Form_Daigou();
        
        // list daigous
        $daigous_table = new Model_DbTable_Daigous();
        $this->view->daigous = $daigous_table->getDaigous();
    }
}