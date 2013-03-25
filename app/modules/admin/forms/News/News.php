<?php

class Admin_Form_News_News extends Zend_Form {

    public function init() {

        $this->setName('news-form');

        $id = new Zend_Form_Element_Hidden('post_id');

        $title = new Zend_Form_Element_Text('title');
        $title->setRequired(true)->setAttrib('class', 'full');
        
        $description = new Zend_Form_Element_Text('description');
        $description->setRequired(true)->setAttrib('class', 'full');
        
        $crop_coords = new Zend_Form_Element_Textarea('crop_coords');
	$crop_coords->setLabel('Crop coords')->setAttribs(array('style' => 'display: none'))->setRequired(true);
        
        $content = new Zend_Form_Element_Textarea('content');
        $content->setRequired(true)->setAttribs(array('class' => 'editor', 'id' => 'editor'));
        
        $language = new Zend_Form_Element_Select('language');
	$language->addMultiOptions(array(
				'fr' => 'French',
				'en' => 'English'
		));
        
        $rdate = new Zend_Form_Element_Text('rdate');
		$rdate->setLabel('Release Date :')->setAttribs(array('id' => 'datepicker'))->setRequired(true)
		->addValidator('Date', true,'d/m/Y');

        $this->addElements(array($id, $title, $description, $crop_coords, $content, $language, $rdate));
        
        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
    }
    
    
}