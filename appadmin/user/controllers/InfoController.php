<?php

/**
 * 用户资料
 */
class User_InfoController extends BaseController
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
     * 查看用户详细资料
     */
    public function indexAction ()
    {
        $user_id = intval($this->_request->uid);

        $this->view->base_info = $this->user_model->get_base_info($user_id);
        $this->view->car_info = $this->user_model->get_car_info($user_id);

        $this->render('info');
    }
    
    /**
     * 详细资料-位置
     */
    public function locateAction ()
    {
        
    }

    /**
     * 详细资料-标签
     */
    public function labelsAction ()
    {
        
    }
    
    public function editinfoAction ()
    {
        $fields = $this->_request->p;
        var_dump($fields);exit;
    }
    
    public function editcarAction ()
    {
        $fields = $this->_request->p;
        var_dump($fields);exit;
    }
    
    public function editcontactAction ()
    {
        $fields = $this->_request->p;
        var_dump($fields);exit;
    }
    
    public function editpasswdAction ()
    {
        $fields = $this->_request->p;
        var_dump($fields);exit;
    }
}
