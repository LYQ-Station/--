<?php
/**
 * 验证码类
 * 
 * 生成验证码图片，并有验证方法
 * 
 * @author Steven
 */
class ValidCode
{
	/**
	 * 生成一张验证码，并在COOKIE中设置验证码内容
	 *
	 * @param string $cookie_name
	 * @param string $font_file
	 * @return image
	 */
	static public function make ($cookie_name, $font_file)
	{
		if (!is_file($font_file))
			return false;
		
		$im = imagecreate(120, 45);
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		
		$alpha = array('1','2','3','4','5','6','7','8','9','0',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N'.'O','P','Q','R','S','T','U','V','W','X','Y','Z'
		);
		
		shuffle($alpha);
		$str = join('', array_slice($alpha, 0, 4));
		
		for ($i = 0; $i < strlen($str); $i++)
		{
			imagettftext($im, 23, rand(-50, 50), ($i * 20 + $i * 7) + 10, 30, $black, $font_file, $str{$i});
		}
		
		for ($i = 0; $i < 4; $i++)
		{
			imagerectangle($im, rand(-10, 100), rand(-10,50), rand(-10, 100), rand(-10,50), $black);
		}
		
		setcookie($cookie_name, substr(md5(strtolower($str)), 0, 8), null, '/');
		return $im;
	}
	
	static public function make2 ($cookie_name, $width, $height, $font_size)
	{
		if (!is_file($font_file))
			return false;
		
		$im = imagecreate($width, $height);
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		
		$alpha = array('1','2','3','4','5','6','7','8','9','0',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N'.'O','P','Q','R','S','T','U','V','W','X','Y','Z'
		);
		
		shuffle($alpha);
		$str = join('', array_slice($alpha, 0, 4));
		
		for ($i = 0; $i < strlen($str); $i++)
		{
			imagettftext($im, $font_size, rand(-30, 30), ($i * $font_size + $i * 3) + 10, $font_size, $black, $font_file, $str{$i});
		}
		
		setcookie($cookie_name, substr(md5(strtolower($str)), 0, 8), null, '/');
		return $im;
	}
	
	/**
	 * 验证
	 *
	 * @param string $vcode
	 * @param string $cookie_name
	 * @return boolean
	 */
	function verify ($vcode, $cookie_name)
	{
		if ('' == $vcode || !isset($_COOKIE[$cookie_name]))
			return false;
			
		if (substr(md5(strtolower($vcode)), 0, 8) == $_COOKIE[$cookie_name])
			return true;
	}
}