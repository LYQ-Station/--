<?php

class UserModel
{
    protected $db;
    
    public function __construct ()
    {
        $this->db = Zend_Registry::get('db');
    }
    
    public function get_list ($page_no = 1, $params = null)
    {
        $page_no = intval($page_no);
        
        $select = $this->db->select()
                ->from(DBTables::USER)
                ->order('regdate DESC');
        
        if (!empty($params))
        {
            if (isset($params['keyword']))
            {
                $select->orWhere('username=?', $params['keyword']);
                $select->orWhere('nickname=?', $params['keyword']);
                $select->orWhere('regdate=?', $params['keyword']);
            }
            else if (isset($params['c']))
            {
                $select->where(SearchFilter::decode($params['c']));
            }
        }
        
        $pager = new Pager($this->db, $select);
        $sql = $pager->get_page($page_no);
        
        $ret = new stdClass();
        $ret->data = $this->db->fetchAll($sql);
        $ret->pager = $pager;
        
        return $ret;
    }
    
    public function get_base_info ($user_id)
    {
        if (0 == intval($user_id))
            throw new Exception ('非法的用户ID');
        
        $select = $this->db->select()
                ->from(array('u'=>DBTables::USER))
                ->joinRight(array('up'=>DBTables::USER_PROFILE), 'u.uid=up.uid')
                ->where('u.uid=?', $user_id)
                ->limit(1);
        
        return $this->db->fetchRow($select);
    }
    
    public function get_car_info ($user_id)
    {
        if (0 == intval($user_id))
            throw new Exception ('非法的用户ID');
        
        $select = $this->db->select()
                ->from(DBTables::USER_CAR)
                ->where('uid=?', $user_id);
        
        return $this->db->fetchRow($select);
    }
    
    public function approval ($user_id)
    {
        $this->db->update(DBTables::USER, array('status'=>0), $this->db->quoteInto('uid = ?', $user_id));
    }
    
    public function lock ($user_id)
    {
        $this->db->update(DBTables::USER, array('status'=>2), $this->db->quoteInto('uid = ?', $user_id));
    }
    
    public function unlock ($user_id)
    {
        $this->db->update(DBTables::USER, array('status'=>0), $this->db->quoteInto('uid = ?', $user_id));
    }
}