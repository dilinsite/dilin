<?php

class Admin_LoginController extends Zend_Controller_Action
{

        
        private $_auth = null;

        
        
	public function init()
	{
		$this->_auth = Zend_Auth::getInstance();
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();
	}
        
        

	public function indexAction()
	{
            $this->view->form = $this->_getForm();
		
	}
        
        
        public function processAction()
        {
            
            $this->_helper->getHelper('viewRenderer')->setNoRender();
            
            if ($this->_request->isXmlHttpRequest()) {
                
                $data = $this->_request->getPost();
                $form = $this->_getForm();
                
                if ($form->isValid($data)) {
                    if ($this->_processAuth($data)) {
                        $this->_helper->json(array('status' => 1, 'data' => $data));
                    } else {
                        $this->_helper->json(array('status' => 0, 'message' => 'error occurred while processing the account authentication.'));
                    }
                } else {
                    $this->_helper->json(array('status' => 0, 'message' => 'We do not recognize your login information. Please try again.'));
                }
            }
        
        }
	
	/**
	 * Not implemented yet
	 */
	public function forgotpasswordAction()
	{
	}
	
        
        
	/**
	 * Process login form
	 * 
	 * @return void
	 */
	public function processNoAjaxAction()
	{
            
		$this->_helper->getHelper('viewRenderer')->setNoRender();
                
		$request = $this->getRequest();
		
		if (!$request->isPost()) {
			return $this->_helper->redirector('index');
		}
		
		$form = $this->_getForm();

		if ($this->_processAuth($request->getPost()) && $form->isValid($request->getPost())) {
			$this->_helper->redirector('index', 'dashboard');
		} else {
			$this->_helper->getHelper('FlashMessenger')->addMessage('We do not recognize your login information. Please try again.');
			$this->_helper->redirector('index');
		}

	}
        

	/**
	 * Logout Action
	 * 
	 * @return void
	 */
	public function logoutAction()
	{
		$this->_helper->getHelper('viewRenderer')->setNoRender();
		
		$this->_auth->clearIdentity();
		$this->_helper->redirector('index');
	}
	
        
        
	/**
	 * Config Zend Auth
	 * 
	 * @return void
	 */
	private function _authAdapter()
	{
		$users = new Admin_Model_DbTable_Users();
		$auth = new Zend_Auth_Adapter_DbTable(null);
		
		$auth->setTableName($users->getTableName())
			->setIdentityColumn($users->getIdentityColumn())
			->setCredentialColumn($users->getCredentialColumn())
			->setCredentialTreatment('MD5(?)');
			
		return $auth;
	}
	
	/**
	 * Check l'user et fourni token Zend Auth dans la session si l'utilisateur est indentifié
	 * 
	 * @param array $values
	 * @return boolean
	 */
	private function _processAuth($values)
	{
		$adapter = $this->_authAdapter();
		$adapter->setIdentity($values['username']);
		$adapter->setCredential($values['password']);
		
		$auth = Zend_Auth::getInstance();
                
		$result = $auth->authenticate($adapter);
		
		if ($result->isValid()) {
			$usersTable = new Admin_Model_DbTable_Users();
			
			$user = $adapter->getResultRowObject(array(
				$usersTable->getPrimaryKey(),
				$usersTable->getIdentityColumn()//,
			//	'display_name'
			));
			
			$usersTable->update(array(
					'last_login_ts' => new Zend_Db_Expr('NOW()'),
					//'ip' =>  $this->getRequest()->getClientIp()
			), array('id = ?' => $user->id));
				
			$auth->getStorage()->write($user);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Login Form
	 * @return Zend_Form
	 */
	private function _getForm()
	{
            $loginForm = new Admin_Form_Login_Login(array(
                'action' => '/admin/login/process',
                'method' => 'post'
            ));
		
            return $loginForm;
	}
}
