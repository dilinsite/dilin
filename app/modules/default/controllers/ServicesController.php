<?php

class ServicesController extends Zend_Controller_Action
{
 
    public function init() {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('metiers', 'widge');
        $actionStack->actionToStack('transport', 'widge');
        $actionStack->actionToStack('work', 'widge');
        $this->table = new Admin_Model_DbTable_Pages();
    }
    
    public function indexAction() {
        $this->_helper->redirector('transport');
    }
    
    public function transportAction() {
        $this->view->page = $this->table->getPageByName('transport');
        $this->render('page');
    }
    
    public function offreterminalisticAction() {
        $this->view->page = $this->table->getPageByName('offre_terminalistic');
        $this->render('page');
    }
    
    public function navitruckingAction() {
        $this->view->page = $this->table->getPageByName('navi_trucking');
        $this->render('page');
    }
    
    public function ferromaritimeAction() {
        $this->view->page = $this->table->getPageByName('ferro_maritime');
        $this->render('page');
    }
    
    public function trackingAction() {
        $this->view->page = $this->table->getPageByName('tracking');
        $this->render('page');
    }
    
    public function quotationAction() {
        $this->view->page = $this->table->getPageByName('quote');
        $form = new Form_Quote(array('method' => 'post'));
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $table = new Model_DbTable_Quote();
                if ($table->addQuote($data)) {
                    //$this->view->success_message = 'Thank you for your request. We will get back to you as soon as possible. ';
                    $this->view->success_message = 'Merci pour votre demande. Nous reviendrons vers vous rapidement';
                    $this->_sentQuoteInquiryByMail($data);
                }
            } else {
                $form->populate($data);
            }
        } else {
            $depart = $this->getRequest()->getParam('depart');
            if ($depart)
                $this->view->depart = $depart;
        }
        $this->view->form = $form;
    }
    
    private function _sentQuoteInquiryByMail($data) {
        $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com',
            array('auth' => 'login',
                'username' => 'dilin@carburant.fr',
                'password' => 'dilin110',
                'ssl' => 'ssl'
        ));
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml('<p>' . $data['mail'] . '</p>');
        $mail->setSubject('DEMANDE DE TARIFICATION');
        $mail->setFrom('dilin@carburant.fr', $data['contact']);
        $mail->addTo('mndoye@naviland-cargo.com', 'mndoye@naviland-cargo.com');
        $mail->setBodyHtml('
        <table id="" width="100%">
            <tr><th align="left">Societe</th><td>' . $data['societe'] . '</td></tr>
            <tr><th align="left">Adresse</th><td>' . $data['adresse'] . '</td></tr>
            <tr><th align="left">Contact</th><td>' . $data['contact'] . '</td></tr>
            <tr><th align="left">Telephone</th><td>' . $data['telephone'] . '</td></tr>
            <tr><th align="left">Fax</th><td>' . $data['fax'] . '</td></tr>
            <tr><th align="left">Mail</th><td>' . $data['mail'] . '</td></tr>
            <tr><th align="left">Type de Transport</th><td>' . $data['type_de_transport'] . '</td></tr>
            <tr><th align="left">Sens</th><td>' . $data['sens'] . '</td></tr>
            <tr><th align="left">Relation demandee</th></tr>
            <tr><td>Depart</td><td>' . $data['depart'] . '</td></tr>
            <tr><td>Arrivee</th><td>' . $data['arrivee'] . '</td></tr>
            <tr><td>Positionnement</td><td>' . $data['positionnement'] . '</td></tr>
            <tr><td>TRO/ZIP</td><td>' . $data['zip'] . '</td></tr>
            <tr><th align="left">Information UTI</th></tr>
            <tr><td>Quantite</td><td>' . $data['quantite'] . '</td></tr>
            <tr><td>Poids Brut</td><td>' . $data['poids_brut'] . '</td></tr>
            <tr><td>Type</td><td>' . $data['type'] . '</td></tr>
            <tr><th align="left">Marchandise dangereuse</th></tr>
            <tr><td>Classe</td><td>' . $data['classe'] . '</td></tr>
            <tr><td>ONU</td><td>' . $data['onu'] . '</td></tr>
            <tr><td>masse</td><td>' . $data['masse'] . '</td></tr>
            <tr><th align="left">Observation</th><td>' . $data['observation'] . '</td></tr>
        </table>
        ');

        $mail->send($mailTransport);
    }
}