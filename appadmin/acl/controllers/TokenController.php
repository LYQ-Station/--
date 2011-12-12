<?php

/*
 * Token
 * 
 */
class Acl_TokenController extends BaseController
{
    /**
     *
     * @var ACLTokenModel
     */
    protected $model;
    
    public function init ()
    {
        $this->model = new ACLTokenModel();
    }
    
    public function listAction ()
    {
        $list = $this->model->get_list($page_no, $params);
        $this->view->items = $list->data;
        $this->view->navigator = $list->pager->get_navigator_str($this->build_url('list',null,null,$params));
        
        print_r($list->data);
		
		$this->render('token-list');
    }
}