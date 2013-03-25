<?php

class Form_News extends Zend_Form
{
 	public function init()
	{
		$this->setName('news-form');
		
		$id = new Zend_Form_Element_Hash('post_id');
		
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title :')->setRequired(true)
		->addValidator('Alnum', true);
		
		$content = new Zend_Form_Element_Textarea('content');
		$content->setLabel('Content:')->setRequired(true)
			->setAttrib('rows', 7)
			->addValidator('Alnum', true);
			
		
		$description = new Zend_Form_Element_Text('description');
		$description->setLabel('Description :')->setRequired(true)
			->addValidator('Alnum', true);
		
		$rdate = new Zend_Form_Element_Text('rdate');
		$rdate->setLabel('Rdate :')->addValidator('Alnum', true);
		
		
		
		$hash = new Zend_Form_Element_Hash('hash');
		
		$submit = new Zend_Form_Element_Submit('submit-news');
		$submit->setLabel('Submit');
		$submit->removeDecorator('DtDdWrapper');
		$submit->addDecorators(array(
            array('HtmlTag', array('tag' => 'dd', 'id' => 'submit-element'))
        ));
		        
		$this->addElements(array($id,$title,  $description, $content,$rdate, $hash, $submit));
	}
	
}
