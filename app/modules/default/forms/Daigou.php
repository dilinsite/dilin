<?php

class Form_Daigou extends Zend_Form
{
    public function init()
    {
        $id = new Zend_Form_Element_Hidden('id');

        $name = new Zend_Form_Element_Text('name');
        $has_img = new Zend_Form_Element_Text('has_img');
        
        $brand = new Zend_Form_Element_Select('brand_id');
        $brand->addMultiOption('', '');
        $brands_table = new Model_DbTable_Brands();
        $brands = $brands_table->getBrands();
        if ($brands) {
            foreach ($brands as $row) {
                $brand->addMultiOption($row['id'], $row['name']);
            }  
        }
        
        $url = new Zend_Form_Element_Text('url');
        $unit_price = new Zend_Form_Element_Text('unit_price');
        $quantity = new Zend_Form_Element_Text('quantity');
        $tax = new Zend_Form_Element_Select('tax');
        $tax->addMultiOptions(array(
            '0.05' => '5%',
            '0.06' => '6%',
            '0.09' => '9%',
            '0' => '0'
        ));
        $buyer = new Zend_Form_Element_Text('buyer');
        $status = new Zend_Form_Element_Select('status');
        $status->addMultiOptions(array(
            'pending' => '待处理中',
            'processing' => '处理中',
            'cancelled' => '已取消',
            'completed' => '已完成'
        ));
        
        $note = new Zend_Form_Element_Text('note');

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array($id, $name,  $has_img, $brand, $url, $unit_price, $quantity, $tax, $buyer, $status, $note, $submit));
        
        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
        
    }
	
}