<?php

/**
 * 权限组控制器
 * 
 */
class Acl_GroupController extends BaseController
{
	protected $grp_model;
	
	public function init ()
	{
		$this->grp_model = new AclGroupModel();
	}
	
	public function indexAction ()
	{
		$this->view->treeview = $this->grp_model->get_list();
        $this->view->grp_options = $this->grp_model->get_options_list();
		
		$this->render('group-index');
	}
  	
	public function addAction ()
	{
		$group = $this->_request->p;
		
        $this->grp_model->add($group);
        
        $this->forward('index');
	}
	
	public function editAction ()
	{
        $id = $this->_request->gid;
        $group = $this->_request->p;

        $this->grp_model->edit($id, $group);

		$this->forward('index');
	}
	
	public function deleteAction ()
	{
		$id = $this->_request->getParam('id');
        $this->grp_model->delete($id);

        $this->forward('index');
	}
}