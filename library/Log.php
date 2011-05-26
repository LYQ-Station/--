<?php

/**
 * 日志类
 * 
 * @author Steven
 * @date 2011-05-26
 */
class Log
{
    /**
     * 日志表
     */
    const TAB_LOG           = 'mc_log';
    
    /**
     * 日志对象组
     *
     * @var array
     */
    static protected $logs_array    = array();

    /**
     *
     * @var Zend_Db
     */
    protected $db;
    
    /**
     *
     * @var Token
     */
    protected $token;
    
    /**
     * 应用名称
     *
     * @var string
     */
    protected $appname;
    
    /**
     * 控制器名
     *
     * @var string
     */
    protected $controller;

    /**
     * 动作名称
     *
     * @var string
     */
    protected $action;
    
    /**
     * 事件
     *
     * @var string
     */
    protected $event;
    
    /**
     * 操作结果
     *
     * @var int
     */
    protected $result;

    /**
     * 压入日志
     *
     * @param string $event
     * @param int $result 
     * @return common_Log
     */
    static public function push ($event, $result = 0)
    {
        $c = __CLASS__;
        
        $log = new $c($event, $result);
        
        array_push(self::$logs_array, &$log);
        
        return $log;
    }
    
    /**
     * 写入日志
     */
    static public function flush ()
    {
        foreach (self::$logs_array as $log)
        {
            $log->write();
        }
        
        array_slice(self::$logs_array, 0);
    }

    /**
     *
     * @param string $event
     * @param int $result 
     */
    protected function __construct ($event, $result = 0)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        
        $this->db = GlobalFactory::get_db();
        $this->token = Token::get_instance();
        
        $this->appname = $request->getModuleName();
        $this->controller = $request->getControllerName();
        $this->action = $request->getActionName();
        
        $this->event = $event;
        $this->result = $result;
    }
    
    public function __set ($var, $val)
    {
        if (!$this->$var)
        {
            return;
        }
        
        $this->$var = $val;
    }
    
    /**
     * 写入日志
     */
    public function write ()
    {
        $this->db->insert(self::TAB_LOG, array(
            'uid'           => $this->token->uid,
            'who'           => $this->token->uname,
            'action'        => "{$this->appname}.{$this->controller}.{$this->action}",
            'event'         => $this->event,
            'result'        => $this->result,
            'dateline'      => time()
        ));
        
        unset($this);
    }
    
}