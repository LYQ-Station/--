<?php

/**
 * 框架控制器基类
 *
 */
class BaseController extends Zend_Controller_Action
{
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

    public function __construct (Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->setRequest($request)
                ->setResponse($response)
                ->_setInvokeArgs($invokeArgs);
        $this->_helper = new Zend_Controller_Action_HelperBroker($this);

        $this->getFrontController()
            ->getPlugin('Zend_Controller_Plugin_ErrorHandler')
            ->setErrorHandler(array(
                'module'		=> $this->_request->getModuleName(),
                'controller'	=> $this->_request->getControllerName(),
                'action'		=> 'error'
            ));
        
        $models_arr = array();
        foreach (Zend_Registry::get('modules')->toArray() as $model)
        {
        	$models_arr[$model['name']] = APPS_PATH . DIRECTORY_SEPARATOR . $model['dir'] . DIRECTORY_SEPARATOR. 'models';
        }
        
        unset($models_arr[$this->_request->getModuleName()]);
        array_unshift($models_arr,  APPS_PATH . DIRECTORY_SEPARATOR . $this->_request->getModuleName() . '/models');
        
        set_include_path(join(PATH_SEPARATOR, $models_arr).PATH_SEPARATOR. get_include_path());
        unset($models_arr);
        
        $config = Zend_Registry::get('config');
        
//        $this->token = Zend_Registry::get('token');
        
//        $this->view->token = $this->token;
        $this->view->request = $this->_request;
        
        $this->view->headTitle($config->web->title, 'SET');
		$this->view->headMeta()->prependName('keywords', $config->web->keywords);
		$this->view->headMeta()->prependName('description', $config->web->description);
        
        $this->view->setScriptPath(APPS_PATH . DIRECTORY_SEPARATOR . $this->_request->getModuleName() . '/views');
//        $this->view->headLink()->appendStylesheet('/css/default.css');
//        $this->view->headScript()->appendFile('/js/jquery142.js');
        
        $this->_helper->viewRenderer->setNoRender();
        
//        $this->cache = Zend_Registry::get('cache');
        
        $this->init();
    }

	public function __destruct ()
    {
        $this->view->clearVars();
    }

    protected function errorAction ()
    {
    	$this->view->exception = $this->_getParam('error_handler')->exception;
    	$log_msg = '';
    	
    	if ($this->view->exception)
    	{
    		$log_msg .= $this->view->exception->getMessage()."\n";
    		$log_msg .= $this->view->exception->getTraceAsString();
    	}
    	else
    	{
    		$log_msg .= 'uncaught exception';
    	}
    }

	/**
	 * 构造地址字符串
	 *
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @param string $query
	 * @return string
	 */
    protected function build_url ($action = null, $controller = null, $module = null, $query = '')
    {
    	$query_str = '';
		if (is_array($query))
		{
			foreach ($query as $key => $val)
			{
				$query_str .= "$key/$val/";
			}
		}
		else 
		{
			$query_str = $query ? $query : '';
		}
		
        $module = $module ? $module : $this->_request->getModuleName();
        $controller = $controller ? $controller : $this->_request->getControllerName();
        $action = $action ? $action : $this->_request->getActionName();

        return "/$module/$controller/$action/$query_str";
    }
    
	/**
	 * 重定向
	 *
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @param string $query
	 */
    protected function forward ($action = null, $controller = null, $module = null, $query = '')
    {
        $this->_redirect($this->build_url($action, $controller, $module, $query));
    }
}
