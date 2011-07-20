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
		$this->view->treeview = $this->model->get_list();
		
		$this->render('premit-basic-index');
	}
}