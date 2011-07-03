<?php

/**
 * 树视图
 * 从数组生成树视图
 *
 * @author Steven
 */
class TreeView
{
	protected $og_arr;
	protected $id_field;
	protected $pid_field;
	protected $item_label;
	protected $item_attrs;
	protected $tree_str;
	
	public function __construct($arr, $node_id = 0, $item_label = '', $item_attrs = null, $id_field = 'id', $pid_field = 'pid')
	{
		$this->og_arr = $arr;
		$this->id_field = $id_field;
		$this->pid_field = $pid_field;
		$this->tree_str = '';
		if (empty($item_label))
		{
			$item_label = '<a href="#" _id="?1" _pid="?2">?3</a>';
			$item_attrs = array('?1' => $id_field, '?2' => $pid_field, '?3' => 'name');
		}
		
		$this->item_label = $item_label;
		$this->item_attrs = $item_attrs;
		$this->make_jquery_treeview($node_id);
	}
	
	public function __toString ()
	{
		return $this->tree_str;
	}
	
	protected function has_children ($node_id)
	{
		$chd_arr = array();
		
		for ($i = 0; $i < count($this->og_arr); $i++)
		{
			if ($this->og_arr[$i][$this->pid_field] == $node_id)
			{
				$chd_arr[] = $this->og_arr[$i];
			}
		}
		return $chd_arr;
	}
	
	protected function make_jquery_treeview ($node_id)
	{
		$chd_idx_arr = $this->has_children($node_id);
		
		if (!empty($chd_idx_arr))
		{
			$this->tree_str .= "<ul>";
			$item_attrs = $this->item_attrs;
			foreach ($chd_idx_arr as $chd_idx)
			{
				//$this->tree_str .=  '<li>'.str_replace(array_keys($this->item_attrs), $chd_idx, $this->item_label);
				
				$this->tree_str .=  '<li>';
				
				$txt = $this->item_label;
				foreach ($this->item_attrs as $attr_k => $attr_v)
				{
					$txt = str_replace($attr_k, $chd_idx[$attr_v], $txt);
				}
				
				$this->tree_str .= $txt;
				
				$res = $this->make_jquery_treeview($chd_idx[$this->id_field]);
				$this->tree_str .=  "</li>";
			}
			$this->tree_str .=  "</ul>";
		}
	}
}