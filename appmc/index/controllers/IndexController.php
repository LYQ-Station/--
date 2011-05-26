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
//        BaseSettings::delete('xx');
//        BaseSettings::get('xx');
//        BaseSettings::set('xx', 32, BaseSettings::T_INT);
//        BaseSettings::set('yy', 62, BaseSettings::T_INT);

        $this->model = new IndexModel();
    }
    
	public function indexAction ()
	{
        $this->words = $this->model->show();
        $this->render('index');
	}
}