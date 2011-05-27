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
        $params = $this->_getAllParams();
        
            //输入数据需要进行验证
        $loginname = addslashes($params['uname']);
        $password = md5(trim($params['upwd']));

            //生产COOKIE序列号
        $snlogin = md5($loginname.$password);
        $snlogin = substr($snlogin, 2, 9);
        $token = Token::create($snlogin);

		if ($token->is_logined())
		{
			setcookie('tsn', $snlogin, -1, '/');
			$this->forward('index', 'index', 'default');
			return;
		}
        
        $adapter = new Zend_Auth_Adapter_DbTable(GlobalFactory::get_db());
        $adapter->setTableName(DBTables::USER)
                ->setIdentityColumn('username')
                ->setCredentialColumn('passwd')
                ->setIdentity($loginname)
                ->setCredential($password);
                
            //进行查询验证
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        
            //没通过验证就跳回到登录页面
        if (!$result->isValid())
        {
        	$this->forward('login');
        	return ;
        }
        
            //通过验证
        $res_obj = $adapter->getResultRowObject();

            //帐号被禁用
		if (0 != $res_obj->status)
		{
			$this->forward('login');
        	return ;
		}

        setcookie('tsn', $snlogin, -1, '/');
        
        $fields = array(
            'sn'		=> $snlogin,
            'uid'		=> $res_obj->id,
			'uname'		=> $res_obj->username,
            'nickname'  => $res_obj->nickname
        );
        
        $token->register($fields);
        
            //跳转到默认首页
        $this->forward('index', 'index', 'default');

    }
    
    public function logoutAction ()
    {
        $this->render('login');
    }
}