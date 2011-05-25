<?php

/**
 * 首页控制器
 *
 */
class Index_IndexController extends BaseController
{
    /**
     *
     * @var IndexModel
     */
    protected $model;

    public function init ()
    {
        $this->model = new IndexModel();
    }
    
	public function indexAction ()
	{
        $this->words = $this->model->show();
        $this->render('index');
	}
}