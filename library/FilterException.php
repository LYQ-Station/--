<?php
/**
 * 过滤器异常类
 *
 * @author Steven
 */
class FilterException extends Exception
{
	const R_AJAX		= 9001;
	
	const R_JS			= 8001;
	
	const R_PAGE		= 7001;
	
	const R_FORWARD		= 6001;
	
	protected $error_data;

	public function __construct ($code, $error_data)
	{
		if (!is_array($error_data) || !is_object($error_data))
		{
			parent::__construct(null, 1);
		}
		else
		{
			parent::__construct($error_data, $code);
		}
		
		$this->error_data = $error_data;
	}
	
	public function get_errors ()
	{
		return $this->error_data;
	}
}