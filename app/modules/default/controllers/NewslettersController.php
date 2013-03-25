<?php

class NewslettersController extends Zend_Controller_Action
{
	/**
	 * Get http request
	 * @var Zend_Controller_Request_Http
	 */
	protected $_request = null;
	
	/**
	 * Initialize controller
	 * 
	 */
    public function init()
    {
    	$this->_request = $this->getRequest();
    }
    
    /**
     * Process 
     * 
     * @return void
     */
    public function indexAction()
    {
    	$this->_helper->getHelper('layout')->disableLayout();
    	$form = $this->_getForm();
    	$this->render('form');
    }
    
    /**
     * Check validate
     *
     * @return JSON
     */
    public function validateAction()
    {
    	$this->_helper->getHelper('layout')->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	 
    	if($this->_request->isXmlHttpRequest()) {
    		$data = $this->_request->getPost();
    		$form = $this->_getForm();
    		
    		$processResult = $form->processAjax($data);
    		
    		if(Zend_Json::decode($processResult) === true) {
    			unset($data['newsletter_submit'], $data['cgu']);
    			$newsletterTable = new Model_DbTable_Newsletter();
    			if($newsletterTable->insert($data)) {
    				Zend_Registry::get('session')->newsletter = $data;
    				
    				$this->_sendMailValidate($data);
    				
    				$this->_helper->json->sendJson(true);
    			}
    		} else {
    			echo $processResult;
    		}
    	}

    }
    
    /**
     * Confirm action after validate success
     * 
     * @return void
     */
    public function confirmAction()
    {
    	$this->_helper->getHelper('layout')->disableLayout();
    }
        
    /**
     * Send confirmation mail after success validation
     *
     * @return boolean
     */
    private function _sendMailValidate($data)
    {
    	$mail = new Zend_Mail('utf-8');
    	$mail->addTo($data['email_addr']);
    	$mail->setSubject('Validation de votre inscription à la newsletter');
    	$mail->setBodyHtml($this->view->render('newsletters/mail/confirm.phtml'));
    		
    	if($mail->send()) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * Get a newsletter form object.
     * 
     * @return Zend_Form
     */
    private function _getForm()
    {
        $newsletterForm = new Form_Newsletter(array(
            'method' => 'post'
        ));
        
        $this->view->newsletterForm = $newsletterForm;
        
        return $newsletterForm;
    }
}