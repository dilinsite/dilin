<?php

class Admin_JobsController extends Zend_Controller_Action 
{

    public function init() 
    {
        Zend_Layout::getMvcInstance()->assign('selectedJobs', true);
        $this->table = new Admin_Model_DbTable_Jobs();
    }

    public function indexAction() 
    {
        $this->view->jobs = $this->table->getJobs(Admin_Model_DbTable_JobCategories::NAME);
        $this->view->form = new Admin_Form_Job();
        $this->view->language = 'all';
    }
    
    public function enAction() 
    {
        $this->view->jobs = $this->table->getJobs(Admin_Model_DbTable_JobCategories::NAME, array('language'=>'en'));
        $this->view->language = 'en';
        $this->render('index');
    }
    
    public function frAction() 
    {
        $this->view->jobs = $this->table->getJobs(Admin_Model_DbTable_JobCategories::NAME, array('language'=>'fr'));
        $this->view->language = 'fr';
        $this->render('index');
    }

    /** 
     * Add a job
     */
    public function addAction() 
    {
        $form = $this->_getForm();
        
        if ($this->_request->isPost()) {
        
            $formData = $this->_request->getPost();

            if ($form->isValid($formData)) {
                $jobs = new Admin_Model_DbTable_Jobs();

                $formData['mdate'] = date('Y-m-d H:i:s');
                $formData['cdate'] = date('Y-m-d H:i:s');

                if ($jobs->addJob($formData))
                    $this->_helper->redirector();
                else
                    throw new Zend_Exception('error occured while adding a new job. ');
            } else {
                $form->populate($formData);
            }
        }
    }

    /**
     * Edit a job
     */
    public function editAction() 
    {
        $form = $this->_getForm();

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $id = $form->getValue('job_id');
                $this->table->updateJob($data, $id);
                $this->_redirect('admin/jobs/view/id/' . $id);
            }
        } else {
            $id = $this->_request->getParam('id');
            if ($id) {
                $formData = $this->table->getJob($id);
                $form->populate($formData->toArray());
            }
            $this->view->id = $id;
        }
    }

    /**
     * Delete a job
     */
    public function deleteAction() 
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $this->table->deleteJob($id);
            $this->_helper->redirector();
        }
    }
    

    /**
     * list jobs
     */
    public function listAction() 
    {
        $table = new Admin_Model_DbTable_Jobs();
        $page = $this->getRequest()->getParam('page', 1);

        $list = $table->getJobs();
        $paginator = Zend_Paginator::factory($list);
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(10);
        $this->view->jobs = $paginator;
        $this->render('index');
    }

    /**
     * View a job
     */
    public function viewAction() 
    {            
        $table = new Admin_Model_DbTable_Jobs();
	$id = $this->getRequest()->getParam('id');
        $this->view->job = $table->getJob($id);
    }

    /**
     * Get a job form object.
     *
     * @return Zend_Form
     */
    private function _getForm() 
    {
        $form = new Admin_Form_Job(array(
            'method' => 'post'
        ));

        // assign view variables
        $this->view->form = $form;
        return $form;
    }
}
