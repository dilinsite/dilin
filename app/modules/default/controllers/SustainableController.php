<?php

class SustainableController extends Zend_Controller_Action {
    
    public function init() {
        $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
        $actionStack->actionToStack('metiers', 'widge');
        $actionStack->actionToStack('quote', 'widge');
        $actionStack->actionToStack('benefit', 'widge');
        $actionStack->actionToStack('transport', 'widge');
        $actionStack->actionToStack('work', 'widge');
        $this->table = new Admin_Model_DbTable_Pages();
    }
    
    public function indexAction() {
        $this->_helper->redirector('policy-qhse');
    }
    
    public function policyQhseAction() {
        $this->view->page = $this->table->getPageByName('policy-qhse');
        $this->render('page');
    }
    
    public function ecocalculatorAction()
	{
	
        $related_origin_station_id = $id = $this->_request->getParam('related_origin_station_id');
        if ($related_origin_station_id) 
            $this->view->related_origin_station_id = $related_origin_station_id;
		
		
		//	If preselected origin is set
		if ($this->_hasParam('id_leaf_origin'))
		{
			$this->view->hasPreselectedOriginPlateforme = true;
			$this->view->idPreselectedOriginPlateforme = (int)$this->_getParam('id_leaf_origin');;
		}
		else
		{
			$this->view->hasPreselectedOriginPlateforme = false;
		}
    }
    
}

?>