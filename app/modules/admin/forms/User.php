<?php

class Admin_Form_User extends Zend_Form {

    
	public function init() {

            
		$this->setName('users-form');
	
                
		// user_id
		$id = new Zend_Form_Element_Hidden('id');
		
                
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
		
		
                
		// status
		$status = $this->createElement('select', 'status');
		$status->setLabel('Status: ')->setRequired(true);
		$status->addMultiOptions(array(
                    'enabled' => 'Enabled',
                    'disabled' => 'Disabled'
		));
                
		
                
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->removeDecorator('DtDdWrapper');
		$submit->addDecorators(array(
				array('HtmlTag', array('tag' => 'dd', 'id' => 'submit-element'))
		));
                
		
                
		$this->addElements(array($id, $username, $password, $password2, $status, $submit));
                
                
		
                foreach($this->getElements() as $element) {
                    $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
                }
                
	}
	
}

?>