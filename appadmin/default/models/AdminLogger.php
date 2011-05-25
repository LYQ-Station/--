<?php
/**
 * 管理日志
 *
 */
class AdminLogger
{
    protected $db;

    public function __construct ()
    {
        $this->db = Zend_Registry::get('db');
    }
    
    public function get_list ($page_no, $params = null)
    {
        $select = $this->db->select()
                ->from(DBTables::ADMIN_LOG)
                ->order('createtime DESC');
        
        if (!empty($params))
        {
            if (isset($params['keyword']))
            {
                $select->orWhere('who=?', $params['keyword']);
                $select->orWhere('event=?', $params['keyword']);
                $select->orWhere('createtime=?', $params['keyword']);
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
}

