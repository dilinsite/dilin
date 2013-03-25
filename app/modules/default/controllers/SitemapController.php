<?php

class SitemapController extends Zend_Controller_Action
{
    public function indexAction() {
        /* disable standard layout, so only the xml of the sitemap is rendered */
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo $this->view->navigation()->sitemap()->render(Zend_Registry::get('navigation_fr'));
    }
}