<?php

/**
 * 关键字过滤
 *
 */
class KeywordsFilterModel
{
    protected $db;
    
    public function __construct ()
    {
        $this->db = Zend_Registry::get('db');
    }
    
    public function get_list ($page_no = 1)
    {
        $select = $this->db->select()
                ->from(DBTables::KW_FILTER);
        
        $pager = new Pager($this->db, $select);
        $sql = $pager->get_page($page_no);
        
        $ret = new stdClass();
        $ret->data = $this->db->fetchAll($sql);
        $ret->pager = $pager;
        
        return $ret;
    }
    
    public function add ($keyword)
    {
        $select = $this->db->select()
                ->from(DBTables::KW_FILTER, 'id')
                ->where('keyword=?', $keyword);
        
        if ($this->db->fetchOne($select))
        {
            throw new Exception('此关键字已经被添加');
        }
        
        $this->db->insert(DBTables::KW_FILTER, array('keyword'=>$keyword));
    }
    
    public function delete ($kid)
    {
        $this->db->delete(DBTables::KW_FILTER, $this->db->quoteInto('id=?', $kid));
    }
    
    public function delete_array ($kids)
    {
        if (!is_array($kids))
            return;
        
        $this->db->delete(DBTables::KW_FILTER, $this->db->quoteInto('id IN (?)', $kids));
    }
    
    public function edit ($kid, $keyword)
    {
        $select = $this->db->select()
                ->from(DBTables::KW_FILTER, 'id')
                ->where('keyword=?', $keyword);
        
        if ($this->db->fetchOne($select))
        {
            throw new Exception('此关键字不存在');
        }
        
        $this->db->update(DBTables::KW_FILTER, array(
            'keyword'   => $keyword
        ), $this->db->quoteInto('id=?', $kid));
    }
    
    public function delete_all ()
    {
        $this->db->delete(DBTables::KW_FILTER, '1=1');
    }
}
