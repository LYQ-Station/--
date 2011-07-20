<?php

/**
 * 基本权限列表
 *
 * @author Steven
 */
class ACLPermitbasicModel extends BaseModel
{
	public function get_list ()
	{
		
	}
	
	public function get_tree ()
	{
		$select = $this->db->select()->from(ACLTables::ACL_PREMIT_FIELD);
		
		return new TreeView(
			$this->db->fetchAll($select),
			0,
			'<a href="#" id="?1" pid="?2" title="?3">?4 [?5]</a>',
			array('?1'=>'id', '?2'=>'pid', '?3'=>'notes', '?4'=>'name', '?5'=>'code')
		);
	}
}

