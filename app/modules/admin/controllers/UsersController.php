<?php

require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';

class Admin_UsersController extends Zend_Controller_Action {

	public function authAction() {
		$request 	= $this->getRequest();
		$registry 	= Zend_Registry::getInstance();
		$auth		= Zend_Auth::getInstance(); 	 
	}
	
	public function indexAction() {
		
		$user_table = new Admin_Model_DbTable_Users();
		
		$user = $user_table->getUser(Zend_Auth::getInstance()->getIdentity()->user_id);
		
		// assign view variables
		$this->view->user = $user;
	} 
	 
	/**
	 * Edit user info
	 */
	public function editAction() {
		$form = new Admin_Form_User();
		$form->setAction("/admin/users/edit")->setMethod('post');
		$form->removeElement('password');
		$form->removeElement('password2');
		
		$users = new Admin_Model_DbTable_Users();
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($_POST)) {
				$data = array(
							'username'		=> $form->getValue('username'),
							'display_name' 	=> $form->getValue('display_name'),
							'status'		=> $form->getValue('status')
						);
				
				$users->editUser($form->getValue('user_id'), $data);
				$this->_helper->redirector(array('action'=>'index'));
			}
		} else {
			$id = $this->getRequest()->getParam('id');
			$formData = $users->getUser($id);
			$form->populate($formData->toArray());
		}
		
		// assign view variables
		$this->view->form = $form;
	}
}
?>