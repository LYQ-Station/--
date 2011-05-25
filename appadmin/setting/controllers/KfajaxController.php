<?php

/**
 * 关键字屏蔽过滤
 * ajax专用操作
 *
 */
class Setting_KfajaxController extends BaseController
{
    /**
     * @var KeywordsFilterModel
     */
    protected $keyword_filter_model;
    
    public function init ()
    {
        $this->_helper->layout->disableLayout();
        
        $this->keyword_filter_model = new KeywordsFilterModel();
    }
    
    public function addAction ()
    {
        try
        {
            $this->keyword_filter_model->add($this->_request->keyword);
        }
        catch (Exception $e)
        {
            AjaxUtils::json_err(1, $e->getMessage());
            return;
        }
        
        AjaxUtils::json('ok');
    }
    
    public function deleteAction ()
    {
        try
        {
            $this->keyword_filter_model->delete($this->_request->id);
        }
        catch (Exception $e)
        {
            AjaxUtils::json_err(1, $e->getMessage());
            return;
        }
        
        AjaxUtils::json('ok');
    }
    
    public function editAction ()
    {
        try
        {
            $this->keyword_filter_model->edit($this->_request->id, $this->_request->keyword);
        }
        catch (Exception $e)
        {
            AjaxUtils::json_err(1, $e->getMessage());
            return;
        }
        
        AjaxUtils::json('ok');
    }
    
    public function deleteselAction ()
    {
        $ids = array_map(intval, explode('-', $this->_request->ids));
        
        $this->keyword_filter_model->delete_array($ids);
        
        AjaxUtils::json('ok');
    }
    
    public function deleteallAction ()
    {
        $this->keyword_filter_model->delete_all();
        
        AjaxUtils::json('ok');
    }
}
