<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
	
	protected function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Admin_',
			'basePath'  => APP_PATH .'/modules/admin',
			'resourceTypes' => array (
				'form' => array(
					'path' => 'forms',
					'namespace' => 'Form',
				),
				'model' => array(
					'path' => 'models',
					'namespace' => 'Model',
				),
			)
		));
		
		return $autoloader;
	}
	
	protected function _initLoadPlugins()
	{
		// On recupérer le Controller Frontal
		$front = Zend_Controller_Front::getInstance();
		// Enregistrement des plugins
		$front->registerPlugin(new Dilin_Controller_Plugin_AdminLogged());
	}
}