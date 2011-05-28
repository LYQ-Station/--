<?php

/**
 * Volume控制器
 *
 * @author Steven
 */
class Resource_VolumeController extends BaseController
{
	/**
	 *
	 * @var VolumeModel
	 */
	protected $model;

	public function init ()
	{
		$this->model = new VolumeModel();
	}

	public function listAction ()
	{
		$page_no = intval($this->_request->page_no);
        
        if ($this->_request->keyword)
        {
            $params['keyword'] = addslashes($this->_request->keyword);
        }
        
        if ($this->_request->c)
        {
            $params['c'] = $this->_request->c;
        }
        
        $list = $this->model->get_list($page_no, $params);
        $this->view->items = $list->data;
        $this->view->navigator = $list->pager->get_navigator_str($this->build_url('list',null,null,$params));
		
		$this->render('resource-list');
	}
	
	public function infoAction ()
	{
		$id = $this->_request->id;
		
		$this->view->item = $this->model->fetch($id);
		
		$this->view->title = $this->view->item['title'];
		
		$this->render('volume-addpage');
	}
	
	public function addpageAction ()
	{
		$rid = $this->_request->rid;
		
		$resource_model = new ResourceModel();
		$resource = $resource_model->fetch($rid);
		
		$this->view->resource = $resource;
		
		$this->view->title = 'Create new Volume';
		$this->view->submit_link = $this->build_url('ajaxadd');
		$this->render('volume-addpage');
	}
	
	public function ajaxaddAction ()
	{
		$this->_helper->layout->disableLayout();
		
		$fields = $this->_request->p;
		$this->model->add($fields);
		
		AjaxUtils::json('ok');
	}
	
	public function ajaxdeleteAction ()
	{
		$this->_helper->layout->disableLayout();
		
		$id = $this->_request->id;
		$this->model->delete($id);
		
		AjaxUtils::json('ok');
	}

	public function searchfieldsAction ()
    {
        $this->_helper->layout->disableLayout();
        
        AjaxUtils::json(array(
            array('l'=>'ID', 'f'=>'id', 't'=>'str'),
            array('l'=>'父ID', 'f'=>'pid', 't'=>'str'),
            array('l'=>'标题', 'f'=>'title', 't'=>'str'),
            array('l'=>'发布时间', 'f'=>'crate_time', 't'=>'date')
        ));
    }
}