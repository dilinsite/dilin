<?php

class Admin_Form_Job extends Zend_Form
{
    
    public function init()
    {
        $id = new Zend_Form_Element_Hidden('job_id');

        $title = new Zend_Form_Element_Text('title');
        $title->setRequired(true)->setAttrib('class', 'full')->setLabel('Title');

        $job_category_table = new Admin_Model_DbTable_JobCategories();
        $job_category_data = $job_category_table->getJobCategories();
        $job_category = new Zend_Form_Element_Select('job_category_id');
        if ($job_category_data) {
            foreach($job_category_data as $j)
                $job_category->addMultiOption($j->job_category_id, $j->name);
        }

        $description = new Zend_Form_Element_Textarea('description');
        $description->setAttribs(array('class' => 'editor', 'id' => 'editor'))->setRequired(true);

        $reference = new Zend_Form_Element_Text('reference');
        $reference->setRequired(true);

        $status = $this->createElement('select', 'status');
        $status->addMultiOptions(array(
            'enabled' => 'Enabled',
            'disabled' => 'Disabled'
        ));

        $language = new Zend_Form_Element_Select('language');
        $language->addMultiOptions(array(
            'fr' => 'French',
            'en' => 'English'
        ));
        
        $this->addElements(array($id, $title, $job_category, $description, $reference, $status, $language));
        
        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
    }
}
