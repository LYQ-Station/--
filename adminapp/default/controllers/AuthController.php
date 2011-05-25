<?php
/**
 * 登录登出控制器
 * 
 */
class AuthController extends BaseController
{
    public function loginAction ()
    {
        $this->render('login');
    }

    public function authAction ()
    {
        if ('admin' != $this->_request->uname ||
            '123' != $this->_request->upwd)
        {
            $this->logger->record('登录', '用户名密码错误', 1);
            $this->forward('login');
            return;
        }
        
        $token = Zend_Registry::get('token');
        $token->uid = 1;
        $token->uname = $this->_request->uname;
        
        $this->logger->info('登录');
        $this->forward('index','index','default');
    }
    
    public function logoutAction ()
    {
        $this->logger->info('登出');
        
        Zend_Session::namespaceUnset('token');
        Zend_Session::destroy(true);
        $this->render('login');
    }
}