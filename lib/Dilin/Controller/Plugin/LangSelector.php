<?php
/**
 * LangSelector Description
 *
 */
class Dilin_Controller_Plugin_LangSelector extends Zend_Controller_Plugin_Abstract {
    
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		$lang = '';
		$locale = new Zend_Locale();
		
                
		if (isset($_COOKIE['locale']) && Zend_Locale::isLocale($_COOKIE['locale'], true, false)) {
                    $locale->setLocale($_COOKIE['locale']);
		} else {
                    $locale->setLocale('fr_FR');
                    $_COOKIE['locale'] = 'fr_FR';
		}

		if ($locale == 'en_US') {
			$lang = 'fr';
		} else { // fr_FR
			$lang = 'fr';
		}
		
		$translate = new Zend_Translate('Array', APP_PATH . '/languages/'. $lang . '.php' , $lang);
		Zend_Registry::set('Zend_Translate', $translate);
                
                $translator = new Zend_Translate(
                    array(
                        'adapter' => 'array',
                        'content' => RESOURCES_LANGUAGES, 
                        'locale'  => $lang,
                        'scan' => Zend_Translate::LOCALE_DIRECTORY
                    )
                );
                Zend_Validate_Abstract::setDefaultTranslator($translator);
    }

}
