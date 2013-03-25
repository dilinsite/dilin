<?php 

class Admin_Form_Page extends Zend_Form {
	
    public function init() {
        $id = new Zend_Form_Element_Hidden('page_id');

        $name = new Zend_Form_Element_Text('name');

        $parent_id = new Zend_Form_Element_Text('parent_id');

        $content = new Zend_Form_Element_Textarea('content');
        $content->setAttribs(array('class' => 'editor', 'id' => 'editor'));
        
        $content_en = new Zend_Form_Element_Textarea('content_en');
        $content_en->setAttribs(array('class' => 'editor', 'id' => 'editor2'));

        $this->addElements(array($id, $name, $parent_id, $content, $content_en));

        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
    }

}

?>