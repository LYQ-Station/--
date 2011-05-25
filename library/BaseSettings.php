<?php

/**
 * 设置类
 *
 */
class BaseSettings
{
    const C_COMMON      = 0;
    
    const C_IMAGE       = 1;
    
    const C_VIDEO       = 2;
    
    const C_VIDEO_EXT   = 3;
    
    const T_INT         = 1;
    
    const T_FLOAT       = 2;
    
    const T_ARRAY       = 3;
    
    static protected $_instance;

    protected $db;

    /**
     *
     * @return BaseSettings 
     */
    static public function get_instance ()
    {
        if (!self::$_instance)
        {
            $c = __CLASS__;
            self::$_instance = new $c;
        }
        
        return self::$_instance;
    }
    
    protected function __construct ()
    {
        $this->db = Zend_Registry::get('db');
    }

    public function fetch ($name, $category =0)
    {
        $select = $this->db->select()
                ->from(DBTables::ADMIN_SETTINGS)
                ->where('category=?', $category);
        
        if ($name)
        {
            $select->where('name=?', $name)->limit(1);
        }
        
        $res = $this->db->query($select);
        
        if (!$res)
            return null;
        
        $ret_array = array();
        while ($arr = $res->fetch())
        {
            switch ($arr['type'])
            {
                case self::T_INT :
                    $arr['value'] = intval($arr['value']);
                    break;

                case  self::T_FLOAT :
                    $arr['value'] = floatval($arr['value']);
                    break;

                case self::T_ARRAY :
                    $arr['value'] = unserialize($arr['value']);
                    break;
            }
            
            $ret_array[$arr['name']] = $arr['value'];
        }
        
        return $ret_array;
    }
    
    public function set ($name, $value, $type, $category)
    {
        switch ($type)
        {
            case self::T_INT :
                $value = intval($value);
                break;
                
            case  self::T_FLOAT :
                $value = floatval($value);
                break;
                
            case self::T_ARRAY :
                $value = serialize($value);
                break;
        }
        
        $rows = $this->db->update(DBTables::ADMIN_SETTINGS, array(
            'value'     => $value,
            'type'      => $type
        ), "category='$category' AND name='$name'");
        
        if (!$rows)
        {
            $this->db->insert(DBTables::ADMIN_SETTINGS, array(
                'category'      => $category,
                'name'          => $name,
                'value'         => $value,
                'type'          => $type
            ));
        }
    }
}