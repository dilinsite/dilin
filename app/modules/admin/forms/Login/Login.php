<?php

class Admin_Form_Login_Login extends Zend_Form
{
 	public function init()
	{
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel('Username')->setAttrib('title', 'Username')->setRequired(true);
		
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password')->setAttrib('title', 'Password')->setRequired(true);

		//$hash = new Zend_Form_Element_Hash('hash');
		//$hash->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$this->addElements(array($username, $password));
                
                foreach($this->getElements() as $element) {
                    $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
                }
	}
}

?>