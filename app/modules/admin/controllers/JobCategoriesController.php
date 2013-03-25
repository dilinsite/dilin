<?php

class Admin_JobCategoriesController extends Zend_Controller_Action 
{
	
    public function init() 
    {
        $this->view->selectedJobs = true;
        $this->table = new Admin_Model_DbTable_JobCategories();
    }

    public function indexAction() 
    {
        $this->view->job_categories = $this->table->getJobCategories();
    }

    /**
     * Add a new job category
     */
    public function addAction() 
    {
        $form = $this->_getForm();

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $this->table->addJobCategory($data['name']);
                $this->_helper->redirector();
            } else {
                $form->populate($data);
            }
        }
    }


    /**
     * Edit job type
     */
    public function editAction() 
    {
        $form = $this->_getForm();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $id = $form->getValue('job_category_id');
                $data = array('name' => $form->getValue('name'));
                if ($this->table->updateJobCategory($data, $id)) {
                    $this->_helper->redirector('index');
                } else {
                    throw new Zend_Exception('error occured while adding a new job category. ');
                }
            }
        } else {
            $id = $this->_request->getParam('id');
            $data = $this->table->getJobCategory($id);
            $form->populate($data->toArray());
        }
    }

    /**
     * Delete a job category
     */
    public function deleteAction() 
    {
        $id = $this->getRequest()->getParam('id');
        if ($this->table->deleteJobCategory('job_category_id = ' . $id)) 
            $this->_helper->redirector();
        else 
            throw new Zend_Exception('error occured while deleting a job category. ');
    }

    /**
     * Get a job form object.
     *
     * @return Zend_Form
     */
    private function _getForm() 
    {
        $form = new Admin_Form_JobCategory(array(
            'method' => 'post'
        ));
                            
        /* assign view variables */
        $this->view->form = $form;
        return $form;
    }
}

