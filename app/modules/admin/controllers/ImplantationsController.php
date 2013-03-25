<?php

class Admin_ImplantationsController extends Zend_Controller_Action 
{
    
    public function init() {
        $this->table = new Admin_Model_DbTable_Implantations();
    }
    
    public function indexAction() {
        $this->view->implantations = $this->table->getImplantations();
    }
    
    public function cnoAction() {
        $form = $this->_getForm();
        
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $id = $form->getValue('implantation_id');
                
                $this->table->updateImplantation($data, $id);
                $this->_helper->redirector();
            }
        } else {
            $id = $this->_request->getParam('id');
            if ($id) {
                $data = $this->table->getImplantation($id);
                $form->populate($data->toArray());
            }
            $this->view->id = $id;
        }
    }
    
    public function anversAction() {
        return $this->_getPage();
    }
    
    public function bordeauxAction() {
        return $this->_getPage();
    }
    
    public function cognacAction() {
        return $this->_getPage();
    }
    
    public function dijonGevreyAction() {
        return $this->_getPage();
    }
    
    public function fosAction() {
        return $this->_getPage();
    }
    
    public function leHavreAction() {
        return $this->_getPage();
    }
    
    public function lyonVenissieuxAction() {
        return $this->_getPage();
    }
    
    public function marseilleAction() {
        return $this->_getPage();
    }
    
    public function strasbourgAction() {
        return $this->_getPage();
    }
    
    public function parisValentonAction() {
        return $this->_getPage();
    }
    
    public function toulouseAction() {
        return $this->_getPage();
    }
    
    private function _getPage() {
        $form = $this->_getForm();
        
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $id = $form->getValue('implantation_id');
                
                $this->table->updateImplantation($data, $id);
                $this->_helper->redirector();
            }
        } else {
            $id = $this->_request->getParam('id');
            if ($id) {
                $data = $this->table->getImplantation($id);
                $form->populate($data->toArray());
            }
            $this->view->id = $id;
        }
        $this->render('form');
    }
    
    private function _getForm() {
        $form = new Admin_Form_Implantation(array(
            'method' => 'post'
        ));

        $this->view->form = $form;
        return $form;
    }
    
}
