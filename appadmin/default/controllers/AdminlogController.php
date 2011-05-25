<?php

/**
 * 管理日志控制器
 *
 */
class AdminlogController extends BaseController
{
    protected $model;

    public function init ()
    {
        $this->model = new AdminLogger();
    }

    public function indexAction ()
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
        $this->view->logs = $list->data;
        $this->view->navigator = $list->pager->get_navigator_str($this->build_url('index',null,null,$params));
        
        $this->render('admin-log-list');
	}
    
    public function searchfieldsAction ()
    {
        $this->_helper->layout->disableLayout();
        
        AjaxUtils::json(array(
            array('l'=>'操作者', 'f'=>'who', 't'=>'str'),
            array('l'=>'事件', 'f'=>'event', 't'=>'str'),
            array('l'=>'状态', 'f'=>'status', 't'=>'str'),
            array('l'=>'操作时间', 'f'=>'dateline', 't'=>'date')
        ));
    }
}