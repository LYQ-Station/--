<?php

class SearchFilter
{
    static public function build_select (&$select, $filter, $params, $relation)
    {
        foreach ($filter as $key => $val)
        {
            if (isset($params[$val]))
            {
                if ('and' == $relation)
                {
                    $select->where($key, $params[$val]);
                }
                else
                {
                    $select->orWhere($key, $params[$val]);
                }
            }
        }
    }
    
    static public function build_select_by_conditions (&$select, $fields, $params)
    {
        if (!$params || !is_array($params))
            return;
        
        foreach ($params as $key => $val)
        {
            //如果是非法的字段则忽略
            if (!in_array($key, $fields))
                continue;
            
            list($v, $order, $like) = explode('-', $val);
            if ('' == $v)
                continue;

            $v = addslashes($v);

            if ($like)
                $select->where("$key like '%$v%'");
            else
                $select->where("$key=?", $v);

            $order = addslashes($order);
            if ($order)
                $select->order("$key $order");
        }
    }
    
    static protected function slashes ($str)
    {
        return str_replace(array('/', '+'), array('|', '-'), $str);
    }
    
    static protected function strip ($str)
    {
        return str_replace(array('|', '-'), array('/', '+'), $str);
    }

    static public function encode ($query)
    {
        if (get_magic_quotes_gpc())
        {
            $query = stripslashes($query);
        }
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        return self::slashes(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 'lbs', $query, MCRYPT_MODE_ECB, $iv)));
        //return urlencode($query);
    }
    
    static public function decode ($query)
    {
        if (get_magic_quotes_gpc())
        {
            $query = stripslashes($query);
        }
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, 'lbs', base64_decode(self::strip($query)), MCRYPT_MODE_ECB, $iv));
        //return urldecode($query);
    }
}