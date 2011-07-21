<?php

/**
 * 树select box
 * 从数组生成select结构的树视图
 *
 * @author Steven
 * 
 * @example
 * 	$tree = new TreeOptions($arr, 0, '<option value="?1">%depth%?2</option>', array('?1'=>'data','?2'=>'title'));
 * 	echo $tree;
 */
class TreeOptions
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
			$item_label = '<option value="?1">%depth%?2</option>';
			$item_attrs = array('?1' => $id_field, '?2' => 'name');
		}
		
		$this->item_label = $item_label;
		$this->item_attrs = $item_attrs;
		$this->make_options_tree($node_id);
	}
	
	public function __toString ()
	{
		return $this->tree_str;
	}
	
	protected function has_children ($node_id)
	{
		$chd_arr = array();
		
		foreach ($this->og_arr as $arr)
		{
			if ($arr[$this->pid_field] == $node_id)
			{
				$chd_arr[] = $arr;
			}
		}
		return $chd_arr;
	}
	
	protected function make_options_tree ($node_id)
	{
		static $depth = 0;
		
		$chd_idx_arr = $this->has_children($node_id);
		
		if (!empty($chd_idx_arr))
		{
			foreach ($chd_idx_arr as $chd_idx)
			{
				$txt = $this->item_label;
				foreach ($this->item_attrs as $attr_k => $attr_v)
				{
					$txt = str_replace($attr_k, $chd_idx[$attr_v], $txt);
				}
				
				$txt = str_replace('%depth%', str_repeat('├', $depth), $txt);
				$this->tree_str .= $txt;
				
				$depth++;
				$this->make_options_tree($chd_idx[$this->id_field]);
				$depth--;
			}
		}
	}
}
