<?php

/**
 * 权限组控制器
 * 
 */
class Acl_PermitbasicController extends BaseController
{
	protected $model;
	
	public function init ()
	{
		$this->model = new ACLPermitbasicModel();
	}
	
	public function indexAction ()
	{
		//$this->view->treeview = $this->model->get_tree('123');
		
		//$this->render('premit-basic-index');
	}
	
	public function listAction ()
	{
		$module_sn = $this->_request->module_sn;
		$page_no = $this->_request->page_no;
		
		$list = $this->model->get_list($module_sn, $page_no);
        $this->view->items = $list->data;
        $this->view->navigator = $list->pager->get_navigator_str($this->build_url('list',null,null,$params));
		
		$this->render('premit-basic-list');
	}
	
	public function treeAction ()
	{
		$this->view->treeview = $this->model->get_tree('123');
		
		$this->render('premit-basic-tree');
	}
}