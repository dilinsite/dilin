<?php

class Form_Contact extends Zend_Form
{
    
    public function init()
    {

        $id = new Zend_Form_Element_Hidden('id');

        $name = new Zend_Form_Element_Text('name');
        $name->setRequired(true);
        
        $email = new Zend_Form_Element_Text('email');
        $email->setRequired(true);

        $tel = new Zend_Form_Element_Text('tel');
        $tel->setRequired(true);

        $content = new Zend_Form_Element_Textarea('content');
        $content->setAttrib('rows', '12');
        $content->setRequired(true);
        
        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array($id, $name, $tel, $email, $content, $submit));
        
        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
        
    }
	
}