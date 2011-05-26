<?php

/**
 * 基础设置类
 *
 */
class BaseSettings
{
    const TAB_SETTINGS  = 'mc_settings';
    
    const T_INT         = 1;
    
    const T_FLOAT       = 2;
    
    const T_ARRAY       = 3;
    
    const T_STRING      = 4;
    
    const DEFAULT_NAMESPACE     = 'core.';

    static public function get ($name, $namespace = null)
    {
        if (empty($name))
            return false;
        
        $db = GlobalFactory::get_db();
        
        if ('*' == $name && null === $namespace)
        {
            $select = $db->select() ->from(self::TAB_SETTINGS);
            echo $select;exit;
            
            $res = $db->query($select);
        
            if (!$res)
                return false;

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

                    case self::T_STRING :
                        break;
                }

                $ret_array[$arr['name']] = $arr['value'];
            }

            return $ret_array;
        }
        elseif ('*' == $name && null !== $namespace)
        {
            $name = $namespace.str_replace('*', '%', $name);
            
            $select = $db->select()
                ->from(self::TAB_SETTINGS)
                ->where('name like ?', $name);
            
            echo $select;exit;
            $res = $db->query($select);
        
            if (!$res)
                return false;

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

                    case self::T_STRING :
                        break;
                }

                $ret_array[$arr['name']] = $arr['value'];
            }

            return $ret_array;
        }
        else
        {
            if (null === $namespace)
            {
                $namespace = self::DEFAULT_NAMESPACE;
            }
            
            $name = $namespace.$name;
            $select = $db->select()
                ->from(self::TAB_SETTINGS)
                ->where('name=?', $name)
                ->limit(1);
            
            echo $select;exit;
            $arr = $db->fetchRow($select);
            
            if (empty($arr))
            {
                return false;
            }
            
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

                case self::T_STRING :
                    break;
            }
            
            return $arr['value'];
        }
    }
    
    static public function set ($name, $value, $type = 4, $namespace = 'core.')
    {
        $name = $namespace . $name;
        
        $db = GlobalFactory::get_db();
        
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
            
            case self::T_STRING :
                break;
        }
        
        $tab = self::TAB_SETTINGS;
        $sql = "REPLACE INTO $tab VALUES (:name, :value, :type)";
        $res = $db->query($sql, array(
            'name'      => $name,
            'value'     => $value,
            'type'      => $type
        ));
        
        return $res->rowCount();
    }
    
    static public function delete ($name, $namespace = 'core.')
    {
        $name = $namespace . $name;
        
        $db = GlobalFactory::get_db();
        
        if ('*' == $name{(strlen($name)-1)})
        {
            return $db->delete(self::TAB_SETTINGS, $db->quoteInto('name like ?', $name));
        }
        else
        {
            return $db->delete(self::TAB_SETTINGS, $db->quoteInto('name=?', $name));
        }
    }
}