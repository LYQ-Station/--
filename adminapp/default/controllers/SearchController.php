<?php

/**
 * 搜索框控制器
 *
 */
class SearchController extends BaseController
{
	public function indexAction ()
	{
        $this->render('popup-search');
	}
    
    public function encodeAction ()
    {
        $this->_helper->layout->disableLayout();
        
        AjaxUtils::json(SearchFilter::encode($this->_request->query));
    }
}