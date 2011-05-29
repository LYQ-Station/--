<?php
/**
 * Volume模型
 * 
 * @author Steven
 */
class VolumeModel extends BaseModel
{
    public function get_list ($id, $page_no = 1, $params = null)
    {
        $select = $this->db->select()
                ->from(DBTables::VOLUME)
                ->order('create_time DESC');
		
		if (0 <= $id)
		{
			$select->where('pid=?', $id);
		}
		
        if (!empty($params))
        {
            if (isset($params['keyword']))
            {
                $select->orWhere('id=?', $params['keyword']);
                $select->orWhere('rid=?', $params['keyword']);
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
	
	public function fetch ($id)
	{
		$select = $this->db->select()->from(DBTables::VOLUME)->where('id=?', $id);
		
		$item = $this->db->fetchRow($select);
		
		return $item;
	}

	public function add (array $fields)
	{
		if (!empty($fields['cover']))
		{
			DirectoryUtils::copy_into(CACHE_PATH.'/'.$fields['cover'], COVER_PATH.date('/Y/m'), true);
			$fields['cover'] = date('/Y/m/') . $fields['cover'];
		}
		
		$fields['create_time'] = TimeUtils::db_time();
		$fields['status'] = 0;
		
		$this->db->insert(DBTables::VOLUME, $fields);
	}
	
	public function edit ($id, array $fields)
	{
		$this->db->update(DBTables::VOLUME, $fields, $this->db->quoteInto('id=?', $id));
	}

	public function delete ($id)
	{
		$this->db->delete(DBTables::VOLUME, $this->db->quoteInto('id=?', $id));
	}
}

