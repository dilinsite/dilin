<?php 

class Admin_Form_LinkCategory extends Zend_Form {
	
	public function init() {
		$id = new Zend_Form_Element_Hidden('link_category_id');

		$name = new Zend_Form_Element_Text('name');
		$name->setRequired(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$description = new Zend_Form_Element_Textarea('description');
		$description->setAttribs(array('class' => 'editor', 'id' => 'editor2'));
		$description->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

		$this->addElements(array($id, $name, $description));
	}

}

?>