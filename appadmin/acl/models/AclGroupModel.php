<?php

/**
 * 权限组处理模型
 * 
 */
class AclGroupModel extends BaseModel
{
	public function get_list ()
	{
		$select = $this->db->select()->from(ACLTables::ACL_GROUP);
		
		return new TreeView(
			$this->db->fetchAll($select),
			0,
			'<a href="#" id="?1" pid="?2" title="?3">?4</a>',
			array('?1'=>'id', '?2'=>'pid', '?3'=>'notes', '?4'=>'name')
		);
	}

    public function get_options_list ()
    {
        $select = $this->db->select()->from(ACLTables::ACL_GROUP);

		return new TreeOptions(
			$this->db->fetchAll($select),
			0
		);
    }

    public function add ($group)
    {
		if (0 >= $group['id'] || 254 < $group['id'])
		{
			throw new Exception('编号只能在1~254之间', 1001);
		}
		
		if (empty($group['pid']))
		{
			$group['pid'] = 0;
			$group['code'] = sprintf('%064x', 0x1<<32);
//			$group['code'] = sprintf('%064x', decbin($group['id']));
//			$group['code'] = dechex($group['id']);
		}
		else
		{
			$select = $this->db->select()
					->from(ACLTables::ACL_GROUP)
					->where('id=?', $group['pid']);
			
			$p_group = $this->db->fetchRow($select);
			
			if (!$p_group)
			{
				throw new Exception('无效的父类别', 1001);
			}
			
			$depth = count(explode('/', $p_group['path']));
			$group['code'] = sprintf('%064x', (dechex($group['id'])>>$depth)^$p_group['code']);
//			$group['code'] = (dechex($group['id'])>>$depth)^$p_group['code'];
		}
		
		echo $group['code'];
		exit;
		
		if ($depth >= 32)
		{
			throw new Exception('组层级不能超过32层', 1002);
		}
		
		unset($group['id']);
		
		$sql = 'INSERT INTO '.ACLTables::ACL_GROUP.' SET pid=:pid,code=UNHEX(:code),name=:name,notes=:notes';
		$this->db->query($sql, $group);
//        $this->db->insert(ACLTables::ACL_GROUP, $group);
		
		$gid = $this->db->lastInsertId();
		
		if (empty($group['pid']))
		{
			$group['path'] = "/$gid";
		}
		else
		{
			$group['path'] = "{$p_group['path']}/$gid";
		}
		
		unset($group['code']);
		
		$this->db->update(ACLTables::ACL_GROUP, $group, $this->db->quoteInto('id=?', $gid));
    }

    public function edit ($id, $group)
    {
        $this->db->update(ACLTables::ACL_GROUP, $group, $this->db->quoteInto("id=?", $id));
    }

    public function delete ($id)
    {
        $this->db->delete(ACLTables::ACL_GROUP, $this->db->quoteInto("id=?", $id));
    }
}