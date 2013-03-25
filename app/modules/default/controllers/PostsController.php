<?php

class PostsController extends Zend_Controller_Action
{

	public function init()
	{
		$actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
		$actionStack->actionToStack('subscribers', 'sidebar');
		$actionStack->actionToStack('top', 'sidebar');
		$actionStack->actionToStack('about', 'sidebar');
		$actionStack->actionToStack('sell', 'sidebar');
	}
	
	/**
	 * Index Action
	 * 
	 * @return void
	 */
	public function indexAction()
	{
		$this->view->body = 'articles';
		
		$cid = $this->getRequest()->getParam('category', 0);
		
		$lastPosts = new Model_DbTable_Posts();
		$lastRowset = $lastPosts->getPostsByCategoryId($cid, 3);
		$this->view->lastRowset = $lastRowset;
		
		// If une post in category redirect post
		if($lastRowset->count() == 1) {
			$lastRow = $lastRowset->toArray();
			$this->_helper->redirector('post', 'posts', 'default', array('id' => $lastRow[0]['post_id']));
		}

		// Others posts
		$page = $this->getRequest()->getParam('page', 1);
		$allPosts = new Model_DbTable_Posts();
		$exclude = $lastPosts->getPostsIdExcludes($lastRowset);
	
		$paginator = Zend_Paginator::factory($allPosts->getPostsByCategoryIdAndExcludePostsId($cid, $exclude));
		$paginator->setCurrentPageNumber($page)->setItemCountPerPage(1);
		$this->view->postsRowset = $paginator;
		
		// Breadcrumb
		$categoriesTable = new Model_DbTable_Categories();
		$categoryRow = $categoriesTable->getCategoryById($cid);
		$this->view->breadcrumb(array(
			array('title' => 'Accueil', 'url' => $this->view->url(array('controller' => 'index', 'action' => 'index'), 'default', true)),
			array('title' => $categoryRow['name'], 'url' => null)
		));
		
		$this->render('list');
	}

	/**
	 * Post Action
	 *
	 * @return void
	 */
	public function postAction()
	{
		$this->view->body = 'post';
		
		$id = $this->getRequest()->getParam('id', 0);

		$postsTable = new Model_DbTable_Posts();
		$postRow = $postsTable->getPostById($id);
		
		if($postRow) {
			$categoriesTable = new Model_DbTable_Categories();
			$categoryRow = $categoriesTable->getCategoryById($postRow['category_id']);
			
			$this->view->breadcrumb(array(
				array('title' => 'Accueil', 'url' => $this->view->url(array('controller' => 'index', 'action' => 'index'), 'default', true)),
				array('title' => $categoryRow['name'], 'url' => $this->view->url(array('controller' => 'posts', 'action' => 'post', 'category' => $categoryRow['category_id']), 'default', true)),
				array('title' => $postRow['title'], 'url' => null)
			));
			
			$this->view->postRow = $postRow;
			$this->view->resourcesRowset = $postsTable->getResources();
		} else {
			$this->_helper->redirector('index', 'index');
		}

	}
}



