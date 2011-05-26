<?php

/**
 * 全局工厂
 * 以延时加载方式，造出各种需要的东西
 *
 * @author Steven
 * @date 2011-05-26
 */
class GlobalFactory
{
	/**
	 * DB单实例
	 *
	 * @var Zend_Db
	 */
	static protected $db;
    
    /**
	 * 创建数据库
	 *
	 * @return Zend_Db
	 */
	static public function get_db ()
	{
        if (!self::$db)
        {
            $config = Zend_Registry::get('config');
            self::$db = Zend_Db::factory($config->db);
            self::$db->query('set names utf8');
        }
        
        return self::$db;
	}
}