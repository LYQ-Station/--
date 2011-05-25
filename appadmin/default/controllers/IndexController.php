<?php

/**
 * 主框架控制器
 *
 */
class IndexController extends BaseController
{
	public function indexAction ()
	{
        $this->_helper->layout->setLayout('admin-frameset-layout');
        $this->render('main');
	}
    
    public function welcomeAction ()
    {
        echo "welcome";
    }
}