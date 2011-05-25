<?php

/**
 * 用户首页控制器
 *
 */
class User_IndexController extends BaseController
{

    /**
     *
     * @var UserModel
     */
    protected $user_model;

    public function init ()
    {
        $this->user_model = new UserModel();
    }

    /**
     * 列表
     */
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
        
        $list = $this->user_model->get_list($page_no, $params);
        $this->view->users = $list->data;
        $this->view->navigator = $list->pager->get_navigator_str($this->build_url('list',null,null,$params));

        $this->render('list');
    }
    
    public function searchfieldsAction ()
    {
        $this->_helper->layout->disableLayout();
        
        AjaxUtils::json(array(
            array('l'=>'用户名', 'f'=>'username', 't'=>'str'),
            array('l'=>'用户昵称', 'f'=>'nickname', 't'=>'str'),
            array('l'=>'电子邮件', 'f'=>'email', 't'=>'str'),
            array('l'=>'注册时间', 'f'=>'regdate', 't'=>'date'),
            array('l'=>'最后登录时间', 'f'=>'lastlogintime', 't'=>'date'),
            array('l'=>'状态', 'f'=>'status', 't'=>'bool')
        ));
    }
}