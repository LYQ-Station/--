<?php
/**
 * 用户身份验证 插件
 * 在进入系统时通过$_COOKIE['snlogin']检查用户是否已经登录
 *
 * @author Steven
 */
class AuthToken extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup (Zend_Controller_Request_Abstract $request)
    {
		$snlogin = $request->snlogin ? $request->snlogin : '123';
		$logger = Zend_Registry::get('logger');
			
		$token = Token::create($snlogin);
		Zend_Registry::set('token', $token);

        if($token->is_logined() == true)
        {
			if ($token->is_expire())
            {
                $logger->info("TOKEN 超时");
                $token->destroy();
				$request->setModuleName('office');
                $request->setControllerName('auth');
                $request->setActionName('login');
            }
        }
		else
        {
				//如果身份令牌为空,则需要跳转至登录页面。 同时,不能阻止正常登录
            if ('auth' != $request->getActionName())
            {
                $request->setModuleName('office');
                $request->setControllerName('auth');
                $request->setActionName('login');
            }
        }
    }
}