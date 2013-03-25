<?php

class Admin_Form_Link extends Zend_Form {
	
	public function init() {

		$this->setName('link-form');
		
		$id = new Zend_Form_Element_Hidden('link_id');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setRequired(true)->setAttrib('class', 'full');
		$name->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$title = new Zend_Form_Element_Text('title');
		$title->setRequired(true)->setAttrib('class', 'full')->setLabel('Title');
		$title->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$job_category_table = new Admin_Model_DbTable_JobCategories();
		$job_category_data = $job_category_table->getJobCategories();
		$job_category = new Zend_Form_Element_Select('job_category_id');
		if ($job_category_data) {
			foreach($job_category_data as $j)
				$job_category->addMultiOption($j->job_category_id, $j->name);
		}
		$job_category->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$description = new Zend_Form_Element_Textarea('description');
		$description->setAttribs(array('class' => 'editor', 'id' => 'editor'))->setRequired(true);
		$description->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$reference = new Zend_Form_Element_Textarea('reference');
		$reference->setAttribs(array('class' => 'editor', 'id' => 'editor2'))->setRequired(true);
		$reference->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$status = $this->createElement('select', 'status');
		$status->addMultiOptions(array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled'
		));
		$status->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
		
		$this->addElements(array($id, $title, $job_category, $description, $reference, $status));
	}
}

?>