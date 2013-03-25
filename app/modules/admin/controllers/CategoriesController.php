<?php

class Admin_CategoriesController extends Zend_Controller_Action
{
	
	public function init() {
		$this->view->selectedCategories = true;
	}

	public function indexAction()
	{
		$table = new Admin_Model_DbTable_Categories();
		//$categories = $table->getCategories(array('status'=>'enabled'),'sort DESC',10);		
		$categories = $table->getCategories(null, 'sort DESC', 10);
		$this->view->categories = $categories;
	}
	
	public function showAction()
	{
		$id = $this->getRequest()->getParam('id', false);		
		$table = new Admin_Model_DbTable_Categories();
		$category = $table -> getCategory($id);
		$this->view->focusRowset = $category;
		$this->render('show');
	}
	
	public function addAction()
	{
		$form = new Admin_Form_Category();
		$form->setAction("/admin/categories/add")->setMethod('post');
		$this->view->form = $form;
		$table = new Admin_Model_DbTable_Categories();
		
	if ($this->getRequest()->isPost()) 
		{
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) 
			{
		
				$name = $form->getValue('name');
			//	$parent_id = $form->getValue('parent_id');
			
				
				$data = array(
						'name'=>$name,
				//		'parent_id'=>$parent_id,		
						);
				$post = new Admin_Model_DbTable_Categories();
				$post->addCategory($data);
				$this->_helper->redirector('index');
			} 
			else
			{
				$form->populate($formData);
			}
		}
	}
	
	/**
	 * Edit category
	 */
	public function editAction() {
		$form = new Admin_Form_Category();
		$form->setAction("/admin/categories/edit")->setMethod('post');
		$table = new Admin_Model_DbTable_Categories();
	
		if ($this->getRequest()->isPost()) {
			
			if ($form->isValid($_POST)) {
				$data = array(
						'name'=>$form->getValue('name'),
						//'parent_id'=>$form->getValue('parent_id'),
						'sort'=>$form->getValue('sort'),
						'locked'=>$form->getValue('locked'),
						'status'=>$form->getValue('status')
				);
				$table->editCategory($data , $form->getValue('category_id'));
				$this->_helper->redirector();
			}
		} else {
			/* populate data if category id exists */
			$id = $this->getRequest()->getParam('id');
			$formData = $table->getCategory($id);
			$form->populate($formData->toArray());
		}
		
		/* assign view variables */
		$this->view->form = $form;
	}
	
	/**
	 * Delete a category by disabling its status
	 */
	public function deleteAction() {
		$categories = new Admin_Model_DbTable_Categories();
	
		$id = $this->getRequest()->getParam('id');
		$categories->deleteCategory($id);
		$this->_helper->redirector('');
	}
	
	public function listAction()
	{
		$table = new Admin_Model_DbTable_Categories();
		$page = $this->getRequest()->getParam('page', 1);
	
		$list = $table->getCategories(array('status'=>'enabled'));
		$paginator = Zend_Paginator::factory($list);
		$paginator->setCurrentPageNumber($page)->setItemCountPerPage(10);
		$this->view->focusRowset = $paginator;
		$this->render('list');
	}
}

