<?php

class Admin_NewsController extends Zend_Controller_Action 
{
    public function init() 
    {
        Zend_Layout::getMvcInstance()->assign('selectedNews', true);
        $this->table = new Admin_Model_DbTable_Posts();
    }

    public function indexAction()  
    {
        $this->view->posts = $this->table->getPosts();
        $this->view->language = 'all';
    }
    
    public function enAction() 
    {
        $this->view->posts = $this->table->getPosts(array('language'=>'en'));
        $this->view->language = 'en';
        $this->render('index');
    }
    
    public function frAction() 
    {
        $this->view->posts = $this->table->getPosts(array('language'=>'fr'));
        $this->view->language = 'fr';
        $this->render('index');
    }
    
    
    /** 
     * Add a news
     */
    public function addAction() 
    {
        $form = $this->_getForm();
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            $data['cdate'] = date('Y-m-d H:i:s');
            if ($form->isValid($data)) {
                $post = new Admin_Model_DbTable_Posts();
                
                if (($post_id = $post->addPost($data))) {
                    $data['post_id'] = $post_id;
                    $crop = $this->_processCrop($data, 524, 410);
                    if($crop) {
                        $this->_rename($data);
                    }
                   $this->_helper->redirector();
                } else 
                   throw new Zend_Exception('error occured while adding news. ');
            } else {
                $form->populate($data);
            }
        }
    }

    /**
     * Edit a news
     */
    public function editAction() 
    {            
        $form = $this->_getForm();
        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($_POST)) {
                $post_id = $formData['post_id'];
                $this->table->updatePost($formData, $post_id);
                $crop = $this->_processCrop($formData, 524, 410);
                if($crop) {
                    $this->_rename($formData);
                }
                $this->_helper->redirector();
            }
        } else {
            $id = $this->getRequest()->getParam('id');			
            $data = $this->table->getPost($id);
            if (is_array($data))
                $form->populate($data);
            else 
                $form->populate($data->toArray());
        }
    }

    /**
     * Delete a news
     */
    public function deleteAction() 
    {            
        $id = $this->getRequest()->getParam('id');		
        $this->table->deletePost($id);
        $this->_helper->redirector();
    }


    /**
     * View a news detail.
     */
    public function viewAction() 
    {
        $table = new Admin_Model_DbTable_Posts();
        $id = $this->getRequest()->getParam('id', false);
        $this->view->news = $table->getPost($id);
    }

    /**
     * Get a job form object.
     *
     * @return Zend_Form
     */
    private function _getForm() 
    {
        $form = new Admin_Form_News_News(array(
            'method' => 'post'
        ));

        $this->view->form = $form;
        return $form;
    }
    
    	 /* Generates the image cut (crop)
	 *
	 * @param array $request
	 * @return boolean
	 */
	private function _processCrop($request, $width, $height)
	{
		if(array_key_exists('crop_coords', $request)) {
	
			if(!empty($request['crop_coords'])) {
				$coords = Zend_Json::decode($request['crop_coords']);
	
				$path = APP_PATH . '/../medias/images/uploads/';
				
				try {
					$image = new Varien_Image($path . $coords['filename']);
					$image->crop($coords['y'], $coords['x'], $coords['w'], $coords['h']);
					$image->resize($width, $height);
					$image->quality(100);
					$image->save($path . 'crop_' . $coords['filename']);
				} catch(Exception $e) {
					return false;
				}
					
				return true;
			}
	
		}
	
		return false;
	}
        
             /* Rename process
	 * 
	 * @param array $request
	 * @return boolean
	 */
	private function _rename($request)
	{
		if(array_key_exists('crop_coords', $request)) {
			if(!empty($request['crop_coords'])) {
				
				$coords = Zend_Json::decode($request['crop_coords']);
				$path = APP_PATH . '/../medias/images/uploads/';
			
                                $ext = end(explode(".", $coords['filename']));
				$old = $path . $coords['filename'];
				$new = $path . $request['post_id'] . '.' . $ext;
				
				$oldCrop = $path . 'crop_' . $coords['filename'];
				$newCrop = $path . 'crop_' . $request['post_id'] . '.' . $ext;
				
				if(rename($old, $new) && rename($oldCrop, $newCrop)) {
					
					$coords['filename'] = $request['post_id'] . '.' . $ext;
					$crop_coords = Zend_Json::encode($coords);
					
					$postsTable = new Admin_Model_DbTable_Posts();
					$postsTable->update(array(
						'crop_coords' => $crop_coords
					), array('post_id = ?' => $request['post_id']));
					
					return true;
				}
			}
		}
		
		return false;
	}
}