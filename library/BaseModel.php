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
	 * @var Token
	 */
    protected $token;

	/**
	 * @var Logger
	 */
    protected $logger;
    
    public function __construct ()
    {
//        $this->cache = Zend_Registry::get('cache');
        
        $this->db = GlobalFactory::get_db();
    }

}
