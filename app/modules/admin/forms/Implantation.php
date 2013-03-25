<?php

class Admin_Form_Implantation extends Zend_Form {
    
    public function init()
    {
        $id = new Zend_Form_Element_Hidden('implantation_id');

        $name = new Zend_Form_Element_Text('name');

        $title = new Zend_Form_Element_Text('title');
        $title->setAttribs(array('class' => 'editor full', 'id' => 'editor'));
        
        $address = new Zend_Form_Element_Textarea('address');
        $address->setAttribs(array('class' => 'editor', 'id' => 'editor2'));
        
        $service_address = new Zend_Form_Element_Textarea('service_address');
        $service_address->setAttribs(array('class' => 'editor', 'id' => 'editor3'));
        
        $hour = new Zend_Form_Element_Textarea('hour');
        $hour->setAttribs(array('class' => 'editor', 'id' => 'editor4'));
        
        $general = new Zend_Form_Element_Textarea('general');
        $general->setAttribs(array('class' => 'editor', 'id' => 'editor5'));
        
        $activities = new Zend_Form_Element_Textarea('activities');
        $activities->setAttribs(array('class' => 'editor', 'id' => 'editor6'));
        
        $acceptance = new Zend_Form_Element_Textarea('acceptance');
        $acceptance->setAttribs(array('class' => 'editor', 'id' => 'editor7'));
        
        $rails = new Zend_Form_Element_Textarea('rails');
        $rails->setAttribs(array('class' => 'editor', 'id' => 'editor8'));
        
        $subtitle = new Zend_Form_Element_Text('subtitle');
        $subtitle->setAttribs(array('class' => 'full'));
        
        $cno_content = new Zend_Form_Element_Textarea('content');
        $cno_content->setAttribs(array('class' => 'editor', 'id' => 'editor9'));
        
        $this->addElements(array($id, $title, $name, $address, $service_address, $hour, $general, $activities, $acceptance, $rails, $subtitle, $cno_content));

        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
    }
}
