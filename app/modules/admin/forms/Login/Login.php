<?php

class Admin_Form_Login_Login extends Zend_Form
{
 	public function init()
	{
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel('Username')->setAttrib('title', 'Username')->setRequired(true);
		$username->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password')->setAttrib('title', 'Password')->setRequired(true);
		$password->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

		$hash = new Zend_Form_Element_Hash('hash');
		$hash->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$this->addElements(array($username, $password, $hash));
	}
}

?>