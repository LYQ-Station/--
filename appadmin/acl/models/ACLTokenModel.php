<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ACLTokenModel extends BaseModel
{
    public function get_list ($page_no = 1, $params = null)
    {
        $select = $this->db->select()
                ->from(DBTables::TOKEN)
                ->order('sync_time');
        
        $pager = new Pager($this->db, $select);
        $sql = $pager->get_page($page_no);
        
        $ret = new stdClass();
        $ret->data = $this->db->fetchAll($sql);
        $ret->pager = $pager;
        
        return $ret;
    }
}