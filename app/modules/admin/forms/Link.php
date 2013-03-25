<?php 

class Admin_Form_Link extends Zend_Form {
	
	public function init() {
		$id = new Zend_Form_Element_Hidden('link_id');
		
		$link_category_id = new Zend_Form_Element_Select('link_category_id');
		$table_link_categories = new Admin_Model_DbTable_LinkCategories();
		$link_categories = $table_link_categories->getLinkCategories();
		if ($link_categories) {
			foreach ($link_categories as $cat) {
				$link_category_id->addMultiOption($cat->link_category_id, $cat->name);
			}
		}
		$link_category_id->setRequired(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$parent_link_id = new Zend_Form_Element('parent_link_id');

		$name = new Zend_Form_Element_Text('name');
		$name->setRequired(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$description = new Zend_Form_Element_Textarea('description');
		$description->setAttribs(array('class' => 'editor', 'id' => 'editor2'));
		$description->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$visible = new Zend_Form_Element_Select('visible');
		$visible->addMultiOptions(array(
				'1' => 'Yes',
				'0' => 'No'	
		));
		$visible->setRequired(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

		$this->addElements(array($id, $link_category_id, $parent_link_id, $name, $description, $visible));
	}

}

?>