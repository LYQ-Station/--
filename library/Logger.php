<?php

/**
 * 后台管理日志
 *
 */
class Logger
{
    static protected $_instance;
    
    protected $db;
    protected $token;

    /**
     *
     * @return Logger
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
        $this->token = Zend_Registry::get('token');
    }
    
    /**
     * 记录日志
     *
     * @param string $event 事件
     * @param string $result 结果
     * @param int $status 状态码
     */
    public function record ($event, $result, $status)
    {
        $this->db->insert(DBTables::ADMIN_LOG, array(
            'who'       => $this->token->uname,
            'event'     => $event,
            'result'    => $result,
            'createtime'=> date('Y-m-d H:i:s'),
            'status'    => $status
        ));
    }
    
    public function info ($event)
    {
        $this->record($event, 'ok', 0);
    }
}
