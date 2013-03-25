<?php

class Admin_LinksController extends Zend_Controller_Action {

	public function init() {
		$this->view->selectedLinks = true;
	}
	
	public function indexAction() {
		$table_jobs = new Admin_Model_DbTable_Jobs();
		$table_job_categories = new Admin_Model_DbTable_JobCategories();
		$jobs = $table_jobs->getJobs($table_job_categories->getTableName());
		$this->view->jobs = $jobs;
		$this->view->form = new Admin_Form_Job();
	}
	
	public function addAction() {
		$form = $this->_getForm();
	//	var_dump($form);
		//die();
	}
	
	/**
	 * Get all the link categories. 
	 */
	public function linkcategoriesAction() {
		$table = new Admin_Model_DbTable_LinkCategories();
		$this->view->link_categories = $table->getLinkCategories();
	}
	
	/**
	 * Add a new Link Category
	 */
	public function addlinkcategoryAction() {
		$form = $this->_getLinkCategoryForm();
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$table_link_cat = new Admin_Model_DbTable_LinkCategories();
				
				if ($table_link_cat->addLinkCategory($formData)) {
					$this->_helper->redirector('linkCategories');
				} 
			}
			$form->populate($formData);
		} 
	}
	
	private function _getForm() {
		$form = new Admin_Form_Link(array(
					'method'=>'post'
				));
		// assign view variables
		$this->view->form = $form;
		return $form;
	}
	
	public function _getLinkCategoryForm() {
		$form = new Admin_Form_LinkCategory(array(
				'method' => 'post'
				));
		// assign view variables
		$this->view->form = $form;
		return $form;
	}

}
?>