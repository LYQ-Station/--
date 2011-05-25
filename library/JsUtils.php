<?php

/**
 * JS实用工具
 * 
 */
class JsUtils
{
	public static function start ()
	{
		echo '<script type="text/javascript">';
	}
	
	public static function end ()
	{
		echo '</script>';
	}
	
	public static function alert ($msg, $isvar = false)
	{
		if(true == $isvar)
		{
			echo 'self.alert('.$msg.');';
			return ;
		}
		echo 'self.alert("'.$msg.'");';
	}
	
	public static function history ($no)
	{
		echo 'self.history.go('.$no.');';
	}
	
	public static function back ()
	{
		echo 'self.history.back();';
	}

	public static function rediect ($url)
	{
		echo "self.location.replace('$url');";
	}
	
	public static function close ()
	{
		echo 'self.close();';
	}
}