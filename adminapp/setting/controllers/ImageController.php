<?php

/**
 * 图片属性设置
 *
 */
class Setting_ImageController extends BaseController
{
    /**
     * @var ImageRefModel
     */
    protected $img_model;
    
    public function init ()
    {
        $this->img_model = new ImageRefModel();
    }
    
    public function indexAction ()
    {
        $img_settings = $this->img_model->get_settings();
        
        $this->view->img_settings = $img_settings;
        $this->render('image');
    }
    
    public function saveAction ()
    {
        $type_arr = $this->_request->type;
        $type_arr = array_combine($type_arr, $type_arr);
        
        $fields = array(
            'type'      => array(
                'value' => $type_arr,
                'type'  => BaseSettings::T_ARRAY
            ),
            'measurement' => array(
                'value' => $this->_request->measurement,
                'type'  => BaseSettings::T_INT
            ),
            'filesize'  => array(
                'value' => $this->_request->filesize,
                'type'  => BaseSettings::T_INT
            )
        );
        
        $this->img_model->edit($fields);
        
        $this->forward('index');
    }
}
