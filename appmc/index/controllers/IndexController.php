<?php

/**
 * 首页控制器
 *
 */
class IndexController extends BaseController
{
	public function indexAction ()
	{
        $this->render('index');
	}
}