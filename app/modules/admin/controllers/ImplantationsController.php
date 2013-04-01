<?php

class Admin_DashboardController extends Zend_Controller_Action 
{
    
    public function init() 
    {
        $this->view->dashboardSelected = true;
    }
    
    public function indexAction() {
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
