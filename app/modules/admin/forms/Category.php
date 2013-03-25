<?php

class Admin_Form_Category extends Zend_Form
{
 	public function init()
	{
		$this->setName('category-form');
		
		$id = new Zend_Form_Element_Hidden('category_id');
		
		$parent_id = new Zend_Form_Element_text('parent_id');
		$parent_id->setLabel('Parent ID: ');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name:')->setRequired(true)
		->addValidator('NotEmpty', true);

		$sort = new Zend_Form_Element_Text('sort');
		$sort->setLabel('Sort:')->addValidator('Int', true);
		
		$locked = new Zend_Form_Element_Select('locked');
		$locked->setLabel('Locked: ')->addValidator('Int', true);
		$locked->addMultiOptions(array(
				'1' => 'Yes',
				'0' => 'No'
		));
		
		$status = new Zend_Form_Element_Select('status');
		$status->setLabel('Status: ');
		$status->addMultiOptions(array(
				'enabled'  => 'Enabled',
				'disabled' => 'Disabled'
		));
		
		$submit = new Zend_Form_Element_Submit('submit-news');
		$submit->setLabel('Submit');
		$submit->removeDecorator('DtDdWrapper');
		$submit->addDecorators(array(
            array('HtmlTag', array('tag' => 'dd', 'id' => 'submit-element'))
        ));
		        
		$this->addElements(array($id, $parent_id, $name, $sort, $locked, $status, $submit));
	}
	
}