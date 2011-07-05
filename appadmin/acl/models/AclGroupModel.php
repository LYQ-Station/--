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
		if (empty($group['pid']))
		{
			$group['pid'] = 0;
		}
		else
		{
			$select = $this->db->select()
					->from(ACLTables::ACL_GROUP)
					->where('id=?', $group['pid']);
			
			$p_group = $this->db->fetchRow($select);
			
			if (!$p_group)
			{
				throw new Exception('无效的父类别', 1002);
			}
		}
		
        $this->db->insert(ACLTables::ACL_GROUP, $group);
		
		$gid = $this->db->lastInsertId();
		
		if (empty($group['pid']))
		{
			$group['path'] = "/$gid";
		}
		else
		{
			$group['path'] = "{$p_group['path']}/$gid";
		}

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