<?php

class AuthPlugin extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $token = new Zend_Session_Namespace('token');
        Zend_Registry::set('token', $token);

        if ('auth' != $request->getControllerName())
        {
            if (empty($token->uid))
            {
                $request->setModuleName('default')->setControllerName('auth')->setActionName('login');
            }
        }
    }

}
