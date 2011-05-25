<?php

/**
 * 构造网站的视图助手类
 * 
 */
class Zend_View_Helper_BuildUrl
{

    public $view;

    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function buildUrl ($action = null, $controller = null, $module = null, $query = '')
    {
        $query_str = '';
		if (is_array($query))
		{
			foreach ($query as $key => $val)
			{
				$query_str .= "$key/$val/";
			}
		}
		else 
		{
			$query_str = $query ? $query : '';
		}
		
        $module = $module ? $module : $this->view->request->getModuleName();
        $controller = $controller ? $controller : $this->view->request->getControllerName();
        $action = $action ? $action : $this->view->request->getActionName();

        return "/$module/$controller/$action/$query_str";
    }

}
