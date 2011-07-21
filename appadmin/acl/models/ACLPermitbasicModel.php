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
	
	public function get_tree ($model_sn)
	{
		$select = $this->db->select()->from(ACLTables::ACL_PERMIT_BASIC)->where('module_sn=?', $model_sn);
        
        $res = $this->db->query($select);
        
        if (!$res || 0 == $res->rowCount())
        {
            return null;
        }
        
        $permits = array();
        
        $pid_stack = array();
        $pid = 0;
        $id = 0;
        
        $id = intval($arr['code'] / 10000000) * 10000000;
        $id = intval($arr['code'] / 100000) * 100000;
        $id = intval($arr['code'] / 1000) * 1000;
        
        while ($arr = $res->fetch())
        {
            $id = intval($arr['code'] / 10000000) * 10000000;
            
            if (!isset($permits[$id]))
            {
                $permits[$id] = array('id' => $id, 'pid' => $pid);
                continue;
            }
            
            $id = intval($arr['code'] / 100000) * 100000;
        }
        
        print_r($permits);exit;
		
		return new TreeView(
			$this->db->fetchAll($select),
			0,
			'<a href="#" id="?1" pid="?2" title="?3">?4 [?5]</a>',
			array('?1'=>'id', '?2'=>'pid', '?3'=>'notes', '?4'=>'name', '?5'=>'code')
		);
	}
}

