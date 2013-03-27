<?php

class Dilin_Controller_Plugin_AdminLogged extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();

        if($module == 'admin') {
        	$auth = Zend_Auth::getInstance();
        	
        	if ($auth->hasIdentity()) {
        		$layout = Zend_Layout::getMvcInstance();
        		$view = $layout->getView();
        		$view->username = $auth->getIdentity()->username;
                        $view->id = $auth->getIdentity()->id;
        	} else {
        		if($controller != 'login') {
        			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        			$redirector->setGotoSimple('index', 'login', 'admin');
        		}
        	}
        }
    }
}
