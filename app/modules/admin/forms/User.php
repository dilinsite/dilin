<?php

class Admin_Form_User extends Zend_Form {
	
	public function init() {
		
		$this->setName('users-form');
		
		// user_id
		$id = new Zend_Form_Element_Hidden('user_id');
		
		// username
		$username = $this->createElement('text', 'username');
		$username->setLabel('Username: ')
				->setRequired(true)
				->addValidator('stringLength', false, array(5, 32))
				->addErrorMessage('Please use between 5 and 32 characters');
		
		// password
		$password = $this->createElement('password', 'password');
		$password->setLabel('Password: ')
				->setRequired(true)
				->addValidator('stringLength', false, array(5, 32))
				->addErrorMessage('Please use between 5 and 32 characters');
		
		// confirm password
		$password2 = $this->createElement('password', 'password2');
		$password2->setLabel('Confirm password: ')
				->setRequired(true)
				->addValidator('identical', false, array('token'=>'password'))
				->addErrorMessage('Passwords are not the same.');
		
		// display name
		$display_name = $this->createElement('text', 'display_name');
		$display_name->setLabel('DISPLAY NAME: ')
					->setRequired(true)
					->addValidator('stringLength', false, array(1, 32))
					->addErrorMessage('Please use between 1 and 32 characters');
		
		// status
		$status = $this->createElement('select', 'status');
		$status->setLabel('Status: ')->setRequired(true);
		$status->addMultiOptions(array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled'
		));
		
		$submit = new Zend_Form_Element_Submit('submit-jobs');
		$submit->setLabel('Submit');
		$submit->removeDecorator('DtDdWrapper');
		$submit->addDecorators(array(
				array('HtmlTag', array('tag' => 'dd', 'id' => 'submit-element'))
		));
		
		$this->addElements(array(
				$id, $username, $password, $password2, $display_name, $status, $submit
		));
		
	}
	
}

?>