<?php
/**
 * 资源模型
 * 
 * @author Steven
 */
class ResourceModel extends BaseModel
{
    public function get_list ($page_no, $params = null)
    {
        $select = $this->db->select()
                ->from(DBTables::RESOURCE)
                ->order('create_time DESC');
        
        if (!empty($params))
        {
            if (isset($params['keyword']))
            {
                $select->orWhere('id=?', $params['keyword']);
                $select->orWhere('pid=?', $params['keyword']);
				$select->orWhere('title=?', $params['keyword']);
                $select->orWhere('create_time=?', $params['keyword']);
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

