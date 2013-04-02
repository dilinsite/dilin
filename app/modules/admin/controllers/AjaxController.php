<?php

class Admin_AjaxController extends Zend_Controller_Action
{

    
    public function init()
    {	
        
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if(!$this->_request->isXmlHttpRequest()) {
             $this->_helper->json(array('status' => 0, 'message' => 'not ajax request'));
        }
        
    }
    
    public function getCategoryAction()
    {
        $id= $this->_request->getParam('id', false);
        
        if ($id) {
            $category_table = new Model_DbTable_Categories();
            $category = $category_table->getCategoryById($id);
            $this->_helper->json(array('status'=>'1', 'data' => $category));
        } else {
            $this->_helper->json(array('status'=>'0', 'message' => 'no ID'));
        }
    }
    
    /**
     * Connect to api and respond back API key and user data
     */
    public function connectAction()
    {
        $id= $this->_request->getParam('id', false);
        
        if ($id) {
            $key = md5('jaguarmission'.$id.time());
            
            $user_table = new Model_DbTable_User();
            $user = $user_table->getUserByWeicoId($id);
            
            if ($user) {
            
                $this->_helper->json(array('state' => '1', 'data' => $user));
                
            } else {
                
                // save user to db
                $new_user = $this->_saveUserInfo($this->_request->getPost(), $key);
                if ($new_user) {
                     $this->_helper->json(array('state' => '1', 'data' => $new_user));
                } else {
                     $this->_helper->json(array('state' => '0', 'message' => 'Error occurred while adding user to DB. '));
                }
            }
            
        } else {
            $this->_helper->json(array('state' => '0', 'message' => 'no ID'));
        }
    }
    
    
    public function createLicenseAction()
    {
        $id= $this->_request->getParam('id', false);
        if ($id) {
            $user_table = new Model_DbTable_User();
            $user = $user_table->getUserByWeicoId($id);
            
            $data = $this->_request->getPost();
            
            // save image
            $this->_saveUrlImage($data['avatar_large'], APP_PATH . '/../medias/uploads/tmp/' . $id . '.jpg');

            $data['avatar_large'] = APP_PATH . '/../medias/uploads/tmp/' . $id . '.jpg';
                
            // create id card
            if ($this->_makeIdCard($data)) {
                //unlink(APP_PATH . '/../medias/uploads/tmp/' . $id . '.jpg');
                $user['license'] = '/license_' . $data['id'] . '.jpg';
                
                $this->_helper->json(array('state' => '1', 'data'=>$user));
                exit;
            } else {
                $this->_helper->json(array('state' => '0', 'message' => "Error occurred while making ID card"));
                exit;
            }
            
        } else {
            $this->_helper->json(array('state' => '0', 'message' => "A valid ID is not provided. "));
            exit;
        }
    }
    
    
    public function missionsCenterAction()
    {
        
        $id= $this->_request->getParam('id', false);
        
        if ($id) {
            $user_table = new Model_DbTable_User();
            $user = $user_table->getUserByWeicoId($id);
            if ($user) {
                
                $this->_helper->json(array(
                    'state' => 1, 
                    'missions' => array(
                        'mission1' => $user['mission1'], 
                        'mission2' => $user['mission2'], 
                        'photo'=> $user['photo']
                        )
                    ));
            }
        }
        
    }
    

    
    /**
     * Get user info
     */
    public function getUserInfoAction()
    {
                        
        $weibo_id= $this->_request->getParam('id', false);

        if ($weibo_id) {
            
            $user_table = new Model_DbTable_User();
            $user = $user_table->getUserByWeicoId($weibo_id);
            
            if ($user) {
                $this->_helper->json(array('state' => 1, 'data' => $user));
            } else {
                $this->_helper->json(array('state' => '0', 'message' => 'no user'));
            }
        } else {
            $this->_helper->json(array('state' => '0', 'message' => 'weibo id is not valid'));
        }
        
    }
    

    /**
     * update mission complete
     */
    public function updateMissionCompleteAction()
    {
        
        $weibo_id= $this->_request->getParam('id', false);

        if ($weibo_id) {
            
            $mission0 = $this->_request->getParam('mission0', false);
            $mission1 = $this->_request->getParam('mission1', false);
            $mission2 = $this->_request->getParam('mission2', false);
            $photo = $this->_request->getParam('photo', false);
            
            if ($mission0 || $mission1 || $mission2 || $photo) {
                $user_table = new Model_DbTable_User();
                $data = array();
                if ($mission0) 
                    $data['mission0'] = $mission0;
                if ($mission1) 
                    $data['mission1'] = $mission1;
                if ($mission2) 
                    $data['mission2'] = $mission2;
                if ($photo) 
                    $data['photo'] = $photo;
                
                if ($data) {
                    $user = $user_table->updateUserGetUser($data, $weibo_id); 
                    echo $this->_helper->json(array('state' => 1, 'data' => $user));
                }
                
            } else {
                echo $this->_helper->json(array('state' => 0));
            }
        }
        echo $this->_helper->json(array('state' => 0));
    }
    
    public function watermarkImageUrlAction()
    {
        $id= $this->_request->getParam('id', false);
        
        if ($id) {
            
            $data = $this->_request->getPost();
            // save image
            $this->_saveImageUrl($data['image'], APP_PATH . '/../medias/images/uploads/original/' . $id . '.jpg');
           
            $data['image'] = APP_PATH . '/../medias/images/uploads/original/' . $id . '.jpg';
            // watermark image
            $result = $this->_watermarkUserImage($data);
            
            if ($result) {
                echo $this->_helper->json(array('state' => 1, 'image_url' => '/medias/images/uploads/large/' . $id . '.jpg' ));
            } else {
                echo $this->_helper->json(array('state' => 0, 'message' => 'failed to watermark the image.'));
            }
        }
    }
    
    public function watermarkImageAction()
    {
        
       
        
        if ($this->getRequest()->isPost()) {

            $id= $this->_request->getParam('id', false);
            
            if ($id && $_FILES) {
                if ($_FILES['file']['error'] == 0 ) {
                    
                    // save image
                   move_uploaded_file( $_FILES['file']['tmp_name'], APP_PATH . '/../medias/uploads/tmp/' . $id . '.jpg');
                   
                   
                    // get photo seq, ranging from 0 to 9
                    $user_media_table = new Model_DbTable_UserMedia();
                    $user_media = $user_media_table->getUserMediaByWeiboId($id);
                    
                        
                        $next_photo_seq = $user_media ? ($user_media['seq'] + 1) : 1;
                        $name = $id . '_' . time() . '_' . $next_photo_seq . '.jpg';
                        
                        
                        
                        $data = array(
                            'id' => $id, 
                            'image' => APP_PATH . '/../medias/uploads/tmp/' . $id . '.jpg', 
                            'name' => $name
                            );

                        $this->_exif($data);
                        // watermark image
                        $result = $this->_watermarkUserImage($data);

                        if ($result) {
                            
                            // update UserMedia
                            if ($user_media) {
                                $user_media_table->updateUserMedia(array('name' => $name, 'seq' => $next_photo_seq), $id);
                            } else {
                                $user_media_table->addUserMedia(array('name' => $name, 'weibo_id' => $id));
                            }
         
                            $name_arr = explode('.', $name);
                            $hd_name = $name_arr['0'] . '@2x' . '.jpg';
                
                            //unlink(APP_PATH . '/../medias/uploads/tmp/' . $id . '.jpg');
                            $this->_helper->json(array(
                                'state' => 1, 
                                'large' => $name,
                                'hd' => $hd_name,
                                'thumbnail' => $name
                                ));
                        } else {
                           // unlink(APP_PATH . '/../medias/uploads/tmp/' . $id . '.jpg');
                            $this->_helper->json(array('state' => 0, 'error' => 'error occurred while watermarking the image.'));
                        }
                            
                   
                } else {
                    $this->_helper->json(array('state' => 0, 'message' => '[file][error] > 0'));
                } 
            } else {
                
                $this->_helper->json(array('state' => 0, 'message' => 'File is bad.'));
            }
        }
         
    }
    
    public function getUserImageThumbnailsAction()
    {
        $user_table = new Model_DbTable_User();
        $users = $user_table->getUsers();
        $thumbnails = array();
        if ($users) {
            foreach ($users as $v) {
                if (file_exists( APP_PATH . '/../medias/images/uploads/thumbnail/'. $v['weibo_id'] . '_' .  $v['photo_seq'] . '.jpg')) {
                    $thumbnails[$v['weibo_id']] = $v['weibo_id'] . '_' .  $v['photo_seq'] . '.jpg';
                }
            }
        }
        
        echo $this->_helper->json($thumbnails);
    }
    
    public function getUserImagesAction()
    {
        $user_media_table = new Model_DbTable_UserMedia();
        $user_medias = $user_media_table->getUserMedias();
        
        $result = array();
        if ($user_medias) {
            foreach ($user_medias as &$user_media) {
                $name_arr = explode('.', $user_media['name']);
                $hd_name = $name_arr['0'] . '@2x' . '.jpg';
                $user_media['hd'] = $hd_name;
            }
            echo $this->_helper->json(array('state'=>1, 'data'=>$user_medias));
        } else 
            echo $this->_helper->json(array('state'=>1, 'data' => $result));
    }
    
    // Draw a border 
    private function _drawBorder(&$img, &$color, $thickness = 2) 
    { 
        $x1 = 0; 
        $y1 = 0; 
        $x2 = ImageSX($img) - 1; 
        $y2 = ImageSY($img) - 1; 

        for($i = 0; $i < $thickness; $i++) 
        { 
            imagerectangle($img, $x1++, $y1++, $x2--, $y2--, $color); 
        } 
    }
    
    /**
     * make id card
     * @param type $data
     * @return type
     */
    private function _makeIdCard($data) 
    {
        
        // create background image
        $bg_img = imagecreatefrompng(APP_PATH . '/../medias/uploads/licenses/license-idcard-bg.png');
        
        
        // create avatar image
        $img_border_color = imagecolorallocate($bg_img, 1, 71, 107);
        imagefilledrectangle($bg_img, 25, 25, 208, 25, $img_border_color);
        imagefilledrectangle($bg_img, 25, 25, 25, 208, $img_border_color);
        imagefilledrectangle($bg_img, 25, 208, 208, 208, $img_border_color);
        imagefilledrectangle($bg_img, 208, 25, 208, 208, $img_border_color);
        $avatar_img = imagecreatefromjpeg($data['avatar_large']);
        $avatar_img_info = getimagesize($data['avatar_large']);
        
        // merge avatar image and bg image
        imagecopyresampled($bg_img, $avatar_img, 27, 27, 0, 0, 180, 180, $avatar_img_info[0], $avatar_img_info[1]);
        imagedestroy($avatar_img);
        
        // water-mark data
        $text_color = imagecolorallocate($bg_img, 238, 238, 238);
        $location =  mb_convert_encoding($data['location'], "html-entities","utf-8" );
        imagettftext($bg_img, 17, 0, 360, 45, $text_color, APP_PATH . "/../medias/fonts/simhei.ttf" , iconv("UTF-8", "UTF-8", $data['name']));
        imagettftext($bg_img, 17, 0, 420, 140, $text_color, APP_PATH . "/../medias/fonts/simhei.ttf" , iconv("UTF-8", "UTF-8", $data['jagfrom'] == 'shanghai' ? '上海' : '北京'));
        imagettftext($bg_img, 17, 0, 555, 95, $text_color, APP_PATH . "/../medias/fonts/simhei.ttf" , $data['gender'] == 'm' ? '男' : '女');
        imagettftext($bg_img, 17, 0, 360, 180, $text_color, APP_PATH . "/../medias/fonts/simhei.ttf" , $location);
        
        // save the image 
        header("content-type: image/jpeg");
        $result = imagejpeg($bg_img, APP_PATH . '/../medias/uploads/licenses/license_' . $data['id'] . '.jpg', 100);
        imagedestroy($bg_img);
        
        return $result;
        
    }
    
    private function _watermarkUserImage($data)
    {
        $name_arr = explode('.', $data['name']); 
        $size = sizeof($name_arr);
        $large_image_name = '@2x.' . $name_arr[$size-1];
        for ($i = 0; $i < sizeof($name_arr) - 1; $i++) {
            $large_image_name = $name_arr[$i] . $large_image_name;
        }
             

        // create background image
        $bg_img = imagecreatefromjpeg($data['image']);
        
        
        list($width, $height) = getimagesize($data['image']);
        $new_width = 460;
        $new_height = ceil($height * 460 / $width);
        $new_width_smaller = 230;
        $new_height_smaller = ceil($height * 230 / $width);
        
        // new image 
        $new_image = imagecreatetruecolor($new_width, $new_height);
        
        // new image smaller 
        $new_image_smaller = imagecreatetruecolor($new_width_smaller, $new_height_smaller);
        
        // resize image
        imagecopyresampled($new_image, $bg_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagecopyresampled($new_image_smaller, $bg_img, 0, 0, 0, 0, $new_width_smaller, $new_height_smaller, $width, $height);
        imagedestroy($bg_img);
        
        // save thumbnail
        $thumbnail_bg_img = imagecreatetruecolor(80, 80);
        $white = imagecolorallocate($thumbnail_bg_img, 255, 255, 255);
        imagefill($thumbnail_bg_img, 0, 0, $white);
        imagealphablending($thumbnail_bg_img, false);
        if ($width > $height) {
            $new_thumbnail_height = 80;
            $new_thumbnail_width = ceil(80 * $width / $height);
            imagecopyresampled($thumbnail_bg_img, $new_image, ceil((80-$new_thumbnail_width)/2), 0, 0, 0, $new_thumbnail_width, $new_thumbnail_height, $new_width, $new_height);
        
       //     $new_thumbnail_height = 80;
       //     $new_thumbnail_width = ceil(80 * $width / $height);
       //     imagecopyresized($thumbnail_bg_img, $new_image, ceil((80-$new_thumbnail_width)/2),0, 0, 0, $new_thumbnail_width, $new_thumbnail_height, $new_width, $new_height);
        } else {
          $new_thumbnail_width = 80;
           $new_thumbnail_height = ceil(80 * $height / $width);
          imagecopyresized($thumbnail_bg_img, $new_image, 0, ceil((80-$new_thumbnail_height)/2),  0, 0, $new_thumbnail_width, $new_thumbnail_height, $new_width, $new_height);
        }
        imagecopyresampled($thumbnail_bg_img, $thumbnail_bg_img,  0, 0, 0, 0, 80, 80, 80, 80);
        imagejpeg($thumbnail_bg_img, APP_PATH . '/../medias/uploads/thumbnails/' . $data['name'], 100);
        imagedestroy($thumbnail_bg_img);
        
        // save 2x thumbnails 
        $thumbnail_2x_bg_img = imagecreatetruecolor(160, 160);
        $white_2x = imagecolorallocate($thumbnail_2x_bg_img, 255, 255, 255);
        imagefill($thumbnail_2x_bg_img, 0, 0, $white_2x);
        imagealphablending($thumbnail_2x_bg_img, false);
        if ($width > $height) {
            $new_thumbnail_2x_height = 160;
            $new_thumbnail_2x_width = ceil(160 * $width / $height);
            imagecopyresampled($thumbnail_2x_bg_img, $new_image, ceil((160-$new_thumbnail_2x_width)/2), 0, 0, 0, $new_thumbnail_2x_width, $new_thumbnail_2x_height, $new_width, $new_height);
        } else {
            $new_thumbnail_2x_width = 160;
            $new_thumbnail_2x_height = ceil(160 * $height / $width);
            imagecopyresampled($thumbnail_2x_bg_img, $new_image, 0, ceil((160-$new_thumbnail_2x_height)/2),  0, 0, $new_thumbnail_2x_width, $new_thumbnail_2x_height, $new_width, $new_height);
        }
        imagecopyresampled($thumbnail_2x_bg_img, $thumbnail_2x_bg_img,  0, 0, 0, 0, 160, 160, 160, 160);
        imagejpeg($thumbnail_2x_bg_img, APP_PATH . '/../medias/uploads/thumbnails/' . $large_image_name, 100);
        imagedestroy($thumbnail_2x_bg_img);
        
        // watermark image
        $logo = imagecreatefrompng(APP_PATH . '/../medias/images/jaguar-watermark@2x.png');
        imagealphablending($logo, true);
        imagesavealpha($logo, true);
        imagecopy($new_image, $logo, 0, $new_height - 59, 0, 0, 200, 59);
        imagedestroy($logo);
        
        
        imagejpeg($new_image, APP_PATH . '/../medias/uploads/larges/' .  $large_image_name , 100);
        imagedestroy($new_image);
        
        // watermark smaller image
        $logo_smaller = imagecreatefrompng(APP_PATH . '/../medias/images/jaguar-watermark.png');
        imagealphablending($logo_smaller, true);
        imagesavealpha($logo_smaller, true);
        imagecopy($new_image_smaller, $logo_smaller, 0, $new_height_smaller - 30, 0, 0, 100, 30);
        imagedestroy($logo_smaller);
        
        imagejpeg($new_image_smaller, APP_PATH . '/../medias/uploads/larges/' . $data['name'], 100);
        imagedestroy($new_image_smaller);
        return true;
    }
    
    /*
     * save image
     */
    private function _saveUrlImage($image_url, $location) 
    {
        
        $c = new Zend_Http_Client();
        $c->setUri($image_url);
        $result = $c->request('GET');        
        $img = imagecreatefromstring($result->getBody());
        imagejpeg($img, $location, 100);
        imagedestroy($img);
        
    }
    
    private function _saveImage($image, $location)
    {
        $info = pathinfo($_FILES['userFile']['name']);
        $ext = $info['extension']; // get the extension of the file
         $newname = "newname.".$ext; 

        move_uploaded_file( $_FILES['image']['tmp_name'], $location);
    }
    
    /**
     * Save user info in db
     * 
     * @param array $data
     * @param int $app_key 
     * @return the user saved
     */
    private function _saveUserInfo($data, $app_key = null) 
    {
        
        $user_table = new Model_DbTable_User();
        
        $data_to_add = array(
            'weibo_id' => $data['id'],
            'name'=>$data['name'], 
            'gender' => $data['gender'],
            'location' => $data['location'],
            'jagfrom' => $data['jagfrom'],
            'avatar_large' => $data['avatar_large']
        );

        if ($app_key) { 
            $data_to_add['app_key'] = $app_key; 
        }
        
        return $user_table->addUserGetUser($data_to_add);
    }
    
    private function _exif($data)
    {
       $exif = exif_read_data($data['image']);
       if (isset($exif['Orientation'])) {
       
            $ort = $exif['Orientation'];
            switch($ort)
            {
                case 2: // horizontal flip
                    $this->_imageFlip($data['image']);
                    break;
                case 3: // 180 rotate left
                    //$data['image'] = imagerotate($data['image'], 180, -1);
                    $this->_imageRotate($data, -90);
                    break;
                case 4: // vertical flip
                    $this->_imageFlip($data['image']);
                    break;
                case 5: // vertical flip + 90 rotate right
                    $this->_imageFlip($data['image']);
                    //$data['image'] = imagerotate($data['image'], -90, -1);
                    $this->_imageRotate($data, -90);
                    break;
                case 6: // 90 rotate right
                   // $data['image'] = imagerotate($data['image'], -90, -1);
                    $this->_imageRotate($data, -90);
                    break;
                case 7: // horizontal flip + 90 rotate right
                    $this->_imageFlip($data['image']);
                    //$data['image'] = imagerotate($data['image'], -90, -1);
                    $this->_imageRotate($data, -90);
                    break;
                case 8: // 90 rotate left
                    //$data['image'] = imagerotate($data['image'], 90, -1);
                    $this->_imageRotate($data, 90);
                    break;

            }
            
       } 
    }
    
    private function _imageRotate($data, $degree)
    {

        // Content type
        header('Content-type: image/jpeg');

        // Load
        $source = imagecreatefromjpeg($data['image']);

        // Rotate
        $rotate = imagerotate($source, $degree, -1);

        // Output
        imagejpeg($rotate, APP_PATH . '/../medias/uploads/tmp/' . $data['id'] . '.jpg', 100);
        imagedestroy($rotate);
    }
           
    
        
    private function _imageFlip(&$image, $x = 0, $y = 0, $width = null, $height = null)
    {

        if ($width  < 1) $width  = imagesx($image);
        if ($height < 1) $height = imagesy($image);

        // Truecolor provides better results, if possible.
        if (function_exists('imageistruecolor') && imageistruecolor($image))
        {
            $tmp = imagecreatetruecolor(1, $height);
        }
        else
        {

            $tmp = imagecreate(1, $height);
        }
        $x2 = $x + $width - 1;
        for ($i = (int)floor(($width - 1) / 2); $i >= 0; $i--)
        {
            // Backup right stripe.
            imagecopy($tmp, $image, 0, 0, $x2 - $i, $y, 1, $height);

            // Copy left stripe to the right.
            imagecopy($image, $image, $x2 - $i, $y, $x + $i, $y, 1, $height);
            // Copy backuped right stripe to the left.
            imagecopy($image, $tmp, $x + $i,  $y, 0, 0, 1, $height);
        }
        imagedestroy($tmp);
        return true;
    }

}

