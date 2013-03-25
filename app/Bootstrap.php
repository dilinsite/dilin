<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
        protected function _initAutoload()
        {
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->setFallbackAutoloader(true);
            return $autoloader;
        }

        
        protected function _initSessionNamespaces()
        {
            $this->bootstrap('session');
            $params = $this->getOption('resources');
            $namespace = new Zend_Session_Namespace($params['session']['name']);
            Zend_Registry::set('session', $namespace);
        }

        
        /**
         * Permet de charger les plugins
         * 
         * @return void
         */
        protected function _initLoadPlugins()
        {
            // On recupérer le Controller Frontal
            $front = Zend_Controller_Front::getInstance();
            // Enregistrement des plugins
            //$front->registerPlugin(new Carburant_Controller_Plugin_ViewSetup());
            $front->registerPlugin(new Dilin_Controller_Plugin_LoadLayout());
            //$front->registerPlugin(new Dilin_Controller_Plugin_LangSelector());
        }
	
    
        protected function _initDb()
        {
		//$multidb = $this->getPluginResource('multidb');
		//Zend_Registry::set('db', $multidb);
        }
        
}

