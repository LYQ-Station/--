<?php

/**
 * 资源控制器
 *
 * @author Steven
 */
class Resource_ResourceController extends BaseController
{
	/**
	 *
	 * @var ResourceModel
	 */
	protected $model;

	public function init ()
	{
		$this->model = new ResourceModel();
	}

	public function listAction ()
	{
		$id = intval($this->_request->id);
		$page_no = intval($this->_request->page_no);
        
        if ($this->_request->keyword)
        {
            $params['keyword'] = addslashes($this->_request->keyword);
        }
        
        if ($this->_request->c)
        {
            $params['c'] = $this->_request->c;
        }
        
        $list = $this->model->get_list($id, $page_no, $params);
        $this->view->items = $list->data;
        $this->view->navigator = $list->pager->get_navigator_str($this->build_url('list',null,null,$params));
		
		$this->view->path_arr = $this->model->get_path_arr($id);
		
		$this->render('resource-list');
	}
	
	public function infoAction ()
	{
		$id = $this->_request->id;
		
		$this->view->item = $this->model->fetch($id);
		
		$this->view->title = $this->view->item['title'];
		$this->view->submit_link = $this->build_url('ajaxedit');
		
		$this->view->volumes = $this->model->get_volumes($id);
		
		$this->render('resource-info');
	}

	public function addpageAction ()
	{
		$this->view->title = 'Create new Resource';
		$this->view->submit_link = $this->build_url('ajaxadd');
		$this->render('resource-addpage');
	}
	
	public function ajaxaddAction ()
	{
		$this->_helper->layout->disableLayout();
		
		$fields = $this->_request->p;
		$this->model->add($fields);
		
		AjaxUtils::json('ok');
	}
	
	public function ajaxeditAction ()
	{
		$this->_helper->layout->disableLayout();
		
		$fields = $this->_request->p;
		$id = $this->_request->id;
		
		$this->model->edit($id, $fields);
		
		AjaxUtils::json('ok');
	}
	
	public function ajaxdeleteAction ()
	{
		$this->_helper->layout->disableLayout();
		
		$id = $this->_request->id;
		$this->model->delete($id);
		
		AjaxUtils::json('ok');
	}
	
	public function uploadAction ()
	{
		$this->_helper->layout->disableLayout();
		
		$uploader = new FileUploader('IMAGE');
		
		try
		{
			$file = $uploader->upload('upload', CACHE_PATH);
			AjaxUtils::json($file['file_name']);
		}
		catch (FileUploaderException $e)
		{
			AjaxUtils::json_err(1, $e->getMessage());
		}
	}
	
	public function coverAction ()
	{
		$this->_helper->layout->disableLayout();
		
		$img_file = $this->_request->cover;
		
		$img_file = CACHE_PATH . '/' . $img_file;
		
		NetUtils::render_image($img_file);
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