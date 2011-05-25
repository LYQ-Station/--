<?php

/**
 * 分页基类
 * 
 */
abstract class PageDivide 
{
		//数据库操作类
	public  $db;
	
		//要进行分页的SQL语句
	protected $sql;
	
		//当前页号
	protected $curr_page_no;
	
		//每页数据条数
	protected $page_recodes;
	
		//总页数
	protected $total_pages;
	
		//总记录数量
	protected $total_recs;
	
	/**
	 * 初始化PageDivide
	 *
	 * @param object $db
	 * @param string $sql 
	 * @param int $page_recs 每页最大显示数
	 */
	function __construct (&$db = null, $sql = '', $page_recs = 30)
	{
		if (empty($db))
			return ;
		
		$this->db = $db;
		$this->page_recodes = intval($page_recs);
		
		if (!empty($db) && !empty($sql))
			$this->divide_page($sql);
	}
	
	/**
	 * 销毁对象
	 *
	 */
	function __destruct()
	{
		$this->db = null;
	}
	
	/**
	 * 设置每页数据条数
	 *
	 * @param int $no
	 */
	public function set_page_recods ($no)
	{
		if (intval($no) !== false)
			$this->page_recodes = intval($no);
	}
	
	/**
	 * 对指定SQL的结果进行分页
	 * （这将覆盖以前的分页结果）
	 *
	 * @param string $sql
	 */
	public function divide_page ($sql, $page_recs = null, $force = false)
	{
		if (empty($sql))
			return;
			
		if ($sql == $this->sql && $force == false)
			return;
			
		if (!empty($page_recs))
			$this->page_recodes = intval($page_recs);
			
		$this->sql = $sql;

		$res = $this->db->query($sql);
		$this->total_recs = $res->rowCount();

		$this->total_pages = ceil($this->total_recs / $this->page_recodes);
		$this->curr_page_no = 1;
	}
	
	/**
	 * 设置当前页并返回当前页sql语句
	 *
	 * @param int $page_no
	 * @return string
	 */
	public function get_page ($page_no)
	{
		if (empty($page_no) || $page_no <=0)
			$page_no = 1;
			
		if (1 != $page_no && $page_no > $this->total_pages)
		{
			$page_no = $this->total_pages;
		}
		
		if (empty($this->total_pages))
			$this->divide_page($this->sql);
			
		$this->curr_page_no = $page_no;
		return $this->sql.' LIMIT '.(($page_no-1)*$this->page_recodes).','.$this->page_recodes;
	}
	
	/**
	 * 导航页码信息
	 *
	 * @return object 属性有（prev_page上一页页号 / next_page下一页页号）
	 */
	public function get_navigator ()
	{
		$navigator = new stdClass();
		
		$navigator->total_pages = $this->total_pages;
		$navigator->total_recs = $this->total_recs;
		$navigator->curr_page_no = $this->curr_page_no;
		
		if ($this->curr_page_no + 1 >= $this->total_pages)
			$navigator->next_page = $this->total_pages;
		else 
			$navigator->next_page = $this->curr_page_no + 1;
			
		if ($this->curr_page_no - 1 <= 0)
			$navigator->prev_page = 1;
		else 
			$navigator->prev_page = $this->curr_page_no - 1;
			
		return $navigator;
	}
}