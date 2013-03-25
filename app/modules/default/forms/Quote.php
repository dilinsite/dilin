<?php

class Form_Quote extends Zend_Form
{

    public function init() {

        $this->setName('eco-calculator-form');
        
        $id = new Zend_Form_Element_Hidden('id');
		
        $societe = new Zend_Form_Element_Text('societe');
        $societe->setAttribs(array('class'=>'full'));
        
        $adresse = new Zend_Form_Element_Text('adresse');
        $adresse->setAttribs(array('class'=>'full'));
        
        $contact = new Zend_Form_Element_Text('contact');
        $contact->setAttribs(array('class'=>'full'));
        
        $telephone = new Zend_Form_Element_Text('telephone');
        $telephone->setAttribs(array('class'=>'full'));
        
        $fax = new Zend_Form_Element_Text('fax');
        $fax->setAttribs(array('class'=>'full'));
        
        $mail = new Zend_Form_Element_Text('mail');
        $mail->setAttribs(array('class'=>'full'))->setRequired(true)
                ->addValidator('EmailAddress', true, array('mx' => true, 'deep' => true));
        
        $type_de_transport = new Zend_Form_Element_Select('type_de_transport');
        $type_de_transport->addMultiOptions(array(
            'fot' => 'FOT à Terminal',
            'owc' => 'OWC (Rail/Route)',
            'ft' => 'RT (Rail/Route Aller-Retour)',
        ));
        
        $sens = new Zend_Form_Element_Select('sens');
        $sens->addMultiOptions(array(
            'import' => 'Import',
            'export' => 'Export',
            'continental' => 'Continental',
            'prestation_annexe' => 'Prestation Annexe'
        ));
        
        $depart = new Zend_Form_Element_Text('depart');
        $arrivee = new Zend_Form_Element_Text('arrivee');
        $positionnement = new Zend_Form_Element_Text('positionnement');
        $zip = new Zend_Form_Element_Text('zip');
        $quantite = new Zend_Form_Element_Text('quantite');
        $poids_brut = new Zend_Form_Element_Select('poids_brut');
        $poids_brut->addMultiOptions(array(
            '<28' => '< 28 TB',
            '<=30' => '<= 30 TB',
            'vide' => 'Vide',
            'autre' => 'Autre'
        ));
        $type = new Zend_Form_Element_Select('type');
        $type->addMultiOptions(array(
            '20_dry_hc' => '20\' DRY',
            '40_dry_hc' => '40\' DRY HC',
            '20_citerne' => '20\' Citerne',
            '30_citerne' => '30\' Citerne',
            'autre' => 'Autre'
        ));
        $classe = new Zend_Form_Element_Text('classe');
        $onu = new Zend_Form_Element_Text('onu');
        $masse = new Zend_Form_Element_Text('masse');
        $observation = new Zend_Form_Element_Textarea('observation');
        $observation->setAttribs(array('rows'=>'2'));
        
        $this->addElements(array($id, $societe, $adresse, $contact, $telephone, 
            $fax, $mail, $type_de_transport, $sens, $depart, 
            $arrivee, $positionnement, $zip, $quantite, $poids_brut,
            $type, $classe, $onu, $masse, $observation));
		
        foreach($this->getElements() as $element) {
            $element->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        }
    }
}