<?php 

class Admin_Form_JobCategory extends Zend_Form 
{	
    public function init() 
    {
        $id = new Zend_Form_Element_Hidden('job_category_id');

        $name = new Zend_Form_Element_Text('name');
        $name->setRequired(true)->setAttrib('class', 'full');

        $this->addElements(array($id, $name));
        
        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
    }

}

?>