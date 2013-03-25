<?php

class Admin_Model_DbTable_Posts extends Zend_Db_Table_Abstract
{
    /**
     * Nom de la table
     * @var string
     */
    protected $_name = 'site_post';

    /**
     * Nom de la clef primaire
     * @var string
     */
    protected $_primary = 'post_id';

    /**
     * Reference de la table (dépendance)
     *
     * @var array
     */
    protected $_referenceMap = array(
            'Category' => array(
                    'columns' => 'category_id',
                    'refTableClass' => 'Model_DbTable_Categories',
                    'refColumns' => 'category_id'
            ),
            'User' => array(
                            'columns' => 'user_id',
                            'refTableClass' => 'Model_DbTable_Users',
                            'refColumns' => 'user_id'
            )
    );

    /**
     * Stock l'objet d'un post
     * @var object
     */
    protected $_resources = null;
    
    public function getTableName() {
        return $this->_name;
    }
    
    public function getPrimaryId() {
        return 'post_id';
    }
    
    public function getNextInsertionId() {
        $select = $this->select()->from($this, array(new Zend_Db_Expr('max(post_id)+1 as id')));
        return $this->fetchRow($select)->id;
    }

    /**
     * get all posts. by default order by the created date in DESC order.
     * 
     * @param $where
     * @param $limit int
     * @return Zend_Db_Table_Rowset
     */
    public function getPosts($where=null, $order=null, $limit = null) {
            $select = $this->select()->where('status != ? ', 'deleted');

            if (is_array($where)) {
                    foreach($where as $k => $v) 
                    $select->where($k . '= ?', $v);
            } else if ($where) {
                $select->where($where);
            } 

            if ($order) 
                    $select->order($order);
            else 
                    $select->order('rdate DESC');

            if(is_int($limit))
                    $select->limit($limit);

            return $this->fetchAll($select);
    }
    
    public function getPublishedPosts($limit = null) {
        $select = $this->select()
                ->where('status = ?', 'published')
                ->where('type = ?', 'news')
                ->order('cdate DESC');

        if(is_int($limit))
            $select->limit($limit);
           
        if (isset($_COOKIE['locale']) && $_COOKIE['locale'] == 'en_US')          
            $select->where ('language = ?', 'en');
        else 
            $select->where('language = ?', 'fr');

        return $this->fetchAll($select);
    }

    /**
     * get post
     *
     * @param $id post_id
     * @return Zend_Db_Table_Row
     * 
     */
    public function getPost($where) {
        if ($where) {
            if (is_numeric($where)) {
                return $this->find($where)->current();
            }
            if (is_string($where)) {
                return $this->fetchRow($where);
            }
        }
        return null;
    }

    /**
     * insert a new post into table
     *
     * @param $data array
     * @return mixed The primary key of the row inserted or 0 if unsuccessful.
     * 
     */
    public function addPost($data) 
    {
        return $data && is_array($data) ? $this->insert($data) : false;
    }

    /**
     * edit table
     *
     * @param $data array
     * @param $id post_id
     * 
     * @return int The number of rows updated
     */
    public function updatePost($data, $where) 
    {
        if ($data && $where) {
            if (is_numeric($where)) {
                return $this->update($data, 'post_id = ' . $where);
            }
            if (is_string($where)) {
                return $this->update($where);
            }
        }
        return 0;
    }

    /**
     * delete table posts by post_id by setting status field to 'deleted')
     *
     * @param $post_id int
     * @return int The number of rows deleted. 
     */
    public function deletePost($id,$status = array('status'=>'deleted')) 
    {
        $status = array('status' => 'deleted');

        if (is_numeric($id)) {
            return $this->update($status, "post_id = " . (int)$id);
        }

        return 0;
    }

    /**
     * Recupere tous les posts non exclu d'une categorie 
     * 
     * @param array $id
     * @return Zend_Db_Table_Rowset
     */
    public function getPostsByCategoryIdAndExcludePostsId($cid, $ids)
    {
            $select = $this->select()
                    ->where('is_active = ?', 1)
                    ->where('category_id = ?', (int) $cid)
                    ->order('post_date DESC');

            if(count($ids) > 0) {
                    $select->where('post_id NOT IN  (?)', (array) $ids);
            }

            return $this->fetchAll($select);
    }

    /**
     * Recupere les posts ID d'une rowset
     * 
     * @param Zend_Db_Table_Rowset $rowset
     * @return array
     */
    public function getPostsIdExcludes(Zend_Db_Table_Rowset $rowset)
    {
            $ids = array();
            foreach($rowset->toArray() as $value) {
                    $ids[$value['post_id']] = $value['post_id'];
            }

            return $ids;
    }

    /**
     * Recupere tous les posts en focus
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getPostsByFocus()
    {
            $select = $this->select()->where('is_active = ?', 1)->where('in_focus = ?', 1)->order('post_date DESC')->order('sort ASC');
            return $this->fetchAll($select);
    }

    /**
     * Recupere tous les posts qui ne sont pas en focus
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getPostsNotByFocus()
    {
            $select = $this->select()->where('is_active = ?', 1)->where('in_focus != ?', 1);
            return $this->fetchAll($select);
    }

    /**
     * Recuperer toutes les resources du post selectionné
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getResources()
    {
            $select = $this->select()->where('is_active = ?', 1);
            $rowset = $this->_resources->findDependentRowset('Model_DbTable_Resources', 'Post', $select);
            return $rowset;
    }

    /**
     * Retourne tous les posts d'une ou plusieurs categorie(s)
     * 
     * @param $id category_id
     * @return Zend_Db_Table_Rowset
     */
    public function getPostsByCategoryId($id, $limit = null)
    {
            $select = $this->select();

            if(is_array($id)) {
                    $select->where('category_id IN (?)', $id);
            } else {
                    $select->where('category_id = ?', (int) $id);
            }

            $select->where('is_active = ?', 1)->order('post_date DESC');

            if(is_int($limit)) {
                    $select->limit($limit);
            }

            return $this->fetchAll($select);
    }
}