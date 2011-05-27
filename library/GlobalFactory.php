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
     * 缓存
     *
     * @var Zend_Cache
     */
    static protected $cache;


    /**
	 * 创建数据库实例
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
    
    /**
     * 创建缓存实例
     *
     * @return Zend_Cache
     */
    static public function get_cache ()
    {
        if (!self::$cache)
        {
            self::$cache = Zend_Cache::factory('Core', 'File',
                array('lifetime' => rand(60, 300), 'automatic_serialization' => true, 'caching' => true),
                array('cache_dir' => SITE_PATH . '/cache')
            );
        }
        
        return self::$cache;
    }
}