<?php

/**
 * 图片设置
 *
 */
class ImageRefModel
{
    protected $db;
    
    public function __construct ()
    {
        $this->db = Zend_Registry::get('db');
    }
    
    public function get_settings ()
    {
        return BaseSettings::get_instance()->fetch(null, BaseSettings::C_IMAGE);
    }
    
    public function edit ($fields)
    {
        foreach ($fields as $n => $f)
        {
            BaseSettings::get_instance()->set($n, $f['value'], $f['type'], BaseSettings::C_IMAGE);
        }
    }
    
}
