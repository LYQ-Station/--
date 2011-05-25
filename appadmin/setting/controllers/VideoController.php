<?php

/**
 * 视频属性设置
 *
 */
class Setting_VideoController extends BaseController
{
    /**
     * @var VideoRefModel
     */
    protected $vdo_model;
    
    public function init ()
    {
        $this->vdo_model = new VideoRefModel();
    }
    
    public function indexAction ()
    {
        $vdo_settings = $this->vdo_model->get_settings();

        $this->view->vdo_settings = $vdo_settings;
        $this->render('video');
    }
    
    public function saveAction ()
    {
        $fields = array(
            'type'      => array(
                'value' => $this->_request->type,
                'type'  => BaseSettings::T_ARRAY
            ),
            'src' => array(
                'value' => $this->_request->src,
                'type'  => BaseSettings::T_ARRAY
            ),
            'filesize'  => array(
                'value' => $this->_request->filesize,
                'type'  => BaseSettings::T_INT
            )
        );
        
        $this->vdo_model->edit($fields);
        
        $this->forward('index');
    }
}
