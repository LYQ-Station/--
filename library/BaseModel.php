<?php

class BaseModel
{
    /**
	 * @var Zend_Db
	 */
    protected $db;

	/**
	 * @var Zend_Cache
	 */
    protected $cache;

	/**
	 * @var Zend_Session_Namespace
	 */
    protected $token;

	/**
	 * @var Logger
	 */
    protected $logger;
    
    public function __construct ()
    {
        $config = Zend_Registry::get('config');
        $this->db = Zend_Db::factory($config->db);
        $this->db->query('set names utf8');
        Zend_Registry::set('db', $this->db);
        
        $this->cache = Zend_Registry::get('cache');
    }

}
