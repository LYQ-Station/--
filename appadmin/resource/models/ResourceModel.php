<?php
/**
 * 资源模型
 * 
 * @author Steven
 */
class ResourceModel extends BaseModel
{
    public function get_list ($id, $page_no = 1, $params = null)
    {
        $select = $this->db->select()
                ->from(DBTables::RESOURCE)
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
	
	public function search_for_complete ($keyword)
	{
		$select = $this->db->select()
                ->from(DBTables::RESOURCE)
				->where("title LIKE '$keyword%'")
                ->order('create_time DESC');
		
		return $this->db->fetchAll($select);
	}

	public function get_path_arr ($id)
	{
		$select = $this->db->select()->from(DBTables::RESOURCE, 'path')->where('id=?', $id);
		
		$path = $this->db->fetchOne($select);
		
		if (!$path)
		{
			return false;
		}
		
		$path_arr = array();
		
		if ('' !== $path)
		{
			$ids = explode(',', $path);
			$select = $this->db->select()->from(DBTables::RESOURCE,array('id', 'title'))->where('id IN (?)', $ids);
			$arr = array();
			$res = $this->db->query($select);
			while ($a = $res->fetch())
			{
				$arr[$a['id']] = $a['title'];
			}
			
			foreach ($ids as $id)
			{
				$path_arr[$id] = $arr[$id];
			}
		}
		
		return $path_arr;
	}
	
	public function get_volumes ($id)
	{
		$select = $this->db->select()->from(DBTables::VOLUME)->where('rid=?', $id);
		
		return $this->db->fetchAll($select);
	}

	public function fetch ($id)
	{
		$select = $this->db->select()->from(DBTables::RESOURCE)->where('id=?', $id);
		
		$item = $this->db->fetchRow($select);
		
		if (!$item)
		{
			return false;
		}
		
		$item['path_arr'] = array();
		
		if ('' !== $item['path'])
		{
			$ids = explode(',', $item['path']);
			$select = $this->db->select()->from(DBTables::RESOURCE)->where('id IN (?)', $ids);
			$arr = array();
			$arr_tmp = array();
			$res = $this->db->query($select);
			while ($a = $res->fetch())
			{
				$arr[$a['id']] = $a['title'];
				$arr_tmp[$a['id']] = $a;
			}
			
			foreach ($ids as $id)
			{
				$item['path_arr'][$id] = $arr[$id];
				$item['parent'] = $arr_tmp[$id];
			}
			
			unset($arr_tmp);
		}
		
		return $item;
	}

	public function add (array $fields)
	{
		if (!empty($fields['cover']))
		{
			DirectoryUtils::copy_into(CACHE_PATH.'/'.$fields['cover'], COVER_PATH.date('/Y/m'), true);
			$fields['cover'] = date('/Y/m/') . $fields['cover'];
		}
		
		if (!empty($fields['pid']))
		{
			$select = $this->db->select()
					->from(DBTables::RESOURCE, 'path')
					->where('id=?', $fields['pid']);
			
			$path = $this->db->fetchOne($select);
			
			if (false === $path)
			{
				$fields['pid'] = 0;
			}
			else
			{
				$fields['path'] = '' === $path ? $fields['pid'] : "{$path},{$fields['pid']}";
			}
		}
		else
		{
			$fields['pid'] = 0;
		}
		
		$fields['create_time'] = TimeUtils::db_time();
		$fields['status'] = 0;
		
		$this->db->insert(DBTables::RESOURCE, $fields);
	}
	
	public function edit ($id, array $fields)
	{
		if (!empty($fields['pid']))
		{
			$select = $this->db->select()
					->from(DBTables::RESOURCE, 'path')
					->where('id=?', $fields['pid']);
			
			$path = $this->db->fetchOne($select);
			
			if (false === $path)
			{
				$fields['pid'] = 0;
			}
			else
			{
				$fields['path'] = '' === $path ? $fields['pid'] : "{$path},{$fields['pid']}";
			}
		}
		else
		{
			$fields['pid'] = 0;
		}
		
		$this->db->update(DBTables::RESOURCE, $fields, $this->db->quoteInto('id=?', $id));
	}

	public function delete ($id)
	{
		$this->db->delete(DBTables::RESOURCE, $this->db->quoteInto('id=?', $id));
	}
}

