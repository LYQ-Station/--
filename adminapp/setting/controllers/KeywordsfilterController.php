<?php

/**
 * 关键字屏蔽过滤
 *
 */
class Setting_KeywordsfilterController extends BaseController
{
    /**
     * @var KeywordsFilterModel
     */
    protected $keyword_filter_model;
    
    public function init ()
    {
        $this->keyword_filter_model = new KeywordsFilterModel();
    }
    
    public function indexAction ()
    {
        $list = $this->keyword_filter_model->get_list($page_no, $params);
        $this->view->keywords = $list->data;
        $this->view->navigator = $list->pager->get_navigator_str($this->build_url('list'));

        $this->render('keywords');
    }
}
