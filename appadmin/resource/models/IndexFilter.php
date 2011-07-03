<?php

/**
 * 资源过滤器
 *
 * @author Steven
 */
class IndexFilter
{
	static public function addpageAction (BaseController $controller)
	{
		$controller->getHelper('layout')->disableLayout();
		
		echo $controller->getRequest()->id;
		$controller->getRequest()->setParam('name', 'StevenLi');
		echo $controller->getRequest()->name;
		
		//var_dump($controller->getRequest());exit;
		
		return false;
	}
}
