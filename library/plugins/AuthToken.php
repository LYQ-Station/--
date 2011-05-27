<?php
/**
 * 用户身份验证
 *
 * @author Steven
 */
class AuthToken extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        echo 111;exit;
		$tsn = $request->tsn ? $request->tsn : '123';
			
		$token = Token::create($tsn);
        var_dump($token);exit;

        if($token->is_logined() == true)
        {
			if ($token->is_expire())
            {
                $token->destroy();
				$request->setModuleName('default');
                $request->setControllerName('auth');
                $request->setActionName('login');
            }
        }
		else
        {
				//如果身份令牌为空,则需要跳转至登录页面。 同时,不能阻止正常登录
            if ('auth' != $request->getActionName())
            {
                $request->setModuleName('default');
                $request->setControllerName('auth');
                $request->setActionName('login');
            }
        }
    }
}