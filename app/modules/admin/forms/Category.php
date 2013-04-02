<?php

class Admin_Form_Category extends Zend_Form
{
    
    public function init()
    {

        $id = new Zend_Form_Element_Hidden('id');

        $controller = new Zend_Form_Element_Text('controller');
        $controller->setRequired(true);

        $name = new Zend_Form_Element_Text('name');
        $name->setRequired(true);

        $status = new Zend_Form_Element_Select('status');
        $status->addMultiOptions(array(
            '1'  => 'Enabled',
            '0' => 'Disabled'
        ));

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array($id, $controller, $name, $status, $submit));
        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
        
    }
	
}