<?php

class TransportMapController extends Zend_Controller_Action {
    
    public function init() {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('calculate', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('metiers', 'widge');
        $actionStack->actionToStack('work', 'widge');
    }
    
    public function indexAction() {
        $this->_helper->redirector('network');
    }
    
    public function networkAction() {
        
    }
    
    public function simulatorAction() {
        $id_leaf_origin = $this->getRequest()->getParam('id_leaf_origin');
        if ($id_leaf_origin) {
            $this->view->id_leaf_origin = $id_leaf_origin;
        }
    }
}

