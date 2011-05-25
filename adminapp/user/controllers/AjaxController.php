<?php

/**
 * 用户首页控制器
 *
 */
class User_AjaxController extends BaseController
{

    /**
     *
     * @var UserModel
     */
    protected $user_model;

    public function init ()
    {
        $this->_helper->layout->disableLayout();
        
        $this->user_model = new UserModel();
    }

    /**
     * 审核
     */
    public function approvalAction ()
    {
        $this->user_model->approval($this->_request->id);
        AjaxUtils::json('ok');
    }

    /**
     * 锁定用户
     */
    public function lockAction ()
    {
        $this->user_model->lock($this->_request->id);
        AjaxUtils::json('ok');
    }

    /**
     * 解锁用户
     */
    public function unlockAction ()
    {
        $this->user_model->unlock($this->_request->id);
        AjaxUtils::json('ok');
    }

}