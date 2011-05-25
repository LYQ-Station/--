<?php

/**
 * 分页类
 * 
 */
class Pager extends PageDivide 
{
	/**
	 * 获取分页代码
	 *
	 * @param string $bas_url 基地址
	 * @param string $query 基地址外部分（不含page_no） 
	 * @param string $style 分页div的css类名
	 * @param int $list_width
	 * @return string
	 */
	public function get_navigator_str ($bas_url = '', $query = null, $style = '', $list_width = 3)
	{
		$nav_info = $this->get_navigator();

			//检查$base_url末尾的‘/’
		$sp = '/' == $bas_url{strlen($bas_url)-1} ? '' : '/';

		$div = '<div class="'.$style.'">';
		
			//如果当前为第一页，则没有上一页
		if ($nav_info->curr_page_no > 1)
			$div .= "&nbsp;<a href=\"{$bas_url}{$query}{$sp}page_no/{$nav_info->prev_page}\">上一页</a>&nbsp;";

			//确定序号栏
		$min = $this->curr_page_no - $list_width;
		if (0 >= $min)
		{
			$min = 1;
			$max = $min + $list_width * 2;
		}
		else
		{
			$max = $this->curr_page_no + $list_width;
		}
		
		if ($max > $this->total_pages)
		{
			$max = $this->total_pages;
			$min = $max - $list_width * 2;
			$min = 0 >= $min ? 1 : $min;
		}

			//打印序号列
		for ($i=$min; $i<=$max; $i++)
		{
			if ($i == $this->curr_page_no)
				$no .= '&nbsp;<a class="on" href="#">'.$i.'</a>';
			else
				$no .= "&nbsp;<a href=\"{$bas_url}{$query}{$sp}page_no/{$i}\">$i</a>";
		}
		$div .= $no;
		
			//如果当前为最后页，则没有下一页
		if ($nav_info->curr_page_no < $this->total_pages)
			$div .= "&nbsp;<a href=\"{$bas_url}{$query}{$sp}page_no/{$nav_info->next_page}\">下一页</a>&nbsp;";

		$div .= "&nbsp;&nbsp;<select onchange=\"self.location.href='{$bas_url}{$query}{$sp}page_no/'+this.value\">";
		
			//下拉选单
		for ($i=1; $i<=$this->total_pages; $i++)
		{
			if ($i == $this->curr_page_no)
				$div .= '<option value="'.$i.'" selected>'.$i.'</option>';
			else
				$div .= '<option value="'.$i.'">'.$i.'</option>';
		}
		$div .= '</select>';
		
		if ($this->total_pages == 0)
			$div = '<div>';
		$div .= '&nbsp;&nbsp;共 '.$this->total_pages.' 页';
		$div .= '</div>';
		
		return $div;
	}
}