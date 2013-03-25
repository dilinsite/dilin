<?php

class Form_Jobs extends Zend_Form
{
 	public function init()
	{
		$this->setName('jobs-form');
		
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title :')->setRequired(false)
		->addValidator('Alnum', true);
		
			
		
		$description = new Zend_Form_Element_Text('description');
		$description->setLabel('Description :')->setRequired(false)
			->addValidator('Alnum', true);
		
		$reference = new Zend_Form_Element_Text('reference');
		$reference->setLabel('Reference :')->setRequired(false)
		->addValidator('Alnum', true);
		
		
		
		$hash = new Zend_Form_Element_Hash('hash');
		
		$submit = new Zend_Form_Element_Submit('submit-jobs');
		$submit->setLabel('Search');
		$submit->removeDecorator('DtDdWrapper');
		$submit->addDecorators(array(
            array('HtmlTag', array('tag' => 'dd', 'id' => 'submit-element'))
        ));
		        
		$this->addElements(array($title,  $description, $reference, $hash, $submit));
	}
	
}