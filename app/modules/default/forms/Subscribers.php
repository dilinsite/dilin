<?php

class Form_Subscribers extends Zend_Form
{
 	public function init()
	{
		$this->setName('subscribers-form');
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email :')->setRequired(true)
			->addValidator('EmailAddress', true, array('mx' => true, 'deep' => true))
			->addFilter('StringToLower');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Nom :')->setRequired(true)
			->addValidator('Alnum', true);
		
		$phone = new Zend_Form_Element_Text('phone');
		$phone->setLabel('Tel. :')->addValidator('Alnum', true, array('allowWhiteSpace' => true));
		
		$options = new Zend_Form_Element_Radio('is_active');
		$options->setRequired(true);
		$options->addMultiOptions(array(
			1 => 'S\'abonner',
			0 => 'Se désabonner'
		));
		
		$hash = new Zend_Form_Element_Hash('hash');
		
		$submit = new Zend_Form_Element_Submit('submit-subscribers');
		$submit->setLabel('Soumission');
		$submit->removeDecorator('DtDdWrapper');
		$submit->addDecorators(array(
            array('HtmlTag', array('tag' => 'dd', 'id' => 'submit-element'))
        ));
		        
		$this->addElements(array($email, $name, $phone, $options, $hash, $submit));
	}
	
}
