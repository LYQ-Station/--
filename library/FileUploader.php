<?php

class FileUploaderException extends Exception
{
    const ERR_EMPTY_EXT     = '没有指定可上传的文件后缀';
    const ERR_NONE_FILE     = '没有文件被上传';
    const ERR_WRONG_EXT     = '不被允许的文件后缀';
    const ERR_MIN_SIZE      = '小于最小尺寸限制';
    const ERR_MAX_SIZE      = '超过最大尺寸限制';
    const ERR_NOT_UPLOADED  = '非法的上传文件';
    const ERR_MOVE          = '无法移动上传的文件';

    public function   __construct ($message, $code = null)
    {
        $message = __CLASS__ . ': ' . $message;
        parent::__construct($message, $code);
    }
}

/**
 * 附件上传类
 * 
 */
class FileUploader
{
    protected static $EXT = array(
        'IMAGE'     => array('jpg', 'gif', 'png', 'bmp'),
        'DOC'       => array('txt', 'rtf', 'doc', 'wps'),
        'MUSIC'     => array('mp3', 'wma', 'ogg'),
        'VIDEO'     => array('avi', 'mp4', '3gp', 'rmvb'),
        'ZIP'       => array('zip', 'tar', 'gz', 'rar', '7z')
    );
    protected $allow_ext_arr;
    protected $deny_ext_arr;
    protected $min_size;
    protected $max_size;
    protected $file_dir;

    public function __construct ($type = null, $extend_type = null, $deny_type = null, $min_size = 1, $max_size = 500000)
    {
        $this->allow_ext_arr = array();
        
        if (!empty($type))
        {
            $type_flag = explode('|', $type);
            foreach ($type_flag as $type)
            {
                if (isset(self::$EXT[$type]))
                {
                    $this->allow_ext_arr = array_merge($this->allow_ext_arr, self::$EXT[$type]);
                }
            }
        }

        if (!empty($extend_type))
        {
            $this->allow_ext_arr = array_merge($this->allow_ext_arr, explode(',', $extend_type));
        }

        $this->deny_ext_arr = explode(',', $deny_type);
        
        if (empty($this->allow_ext_arr) && empty($this->deny_ext_arr))
        {
            throw new FileUploaderException(FileUploaderException::ERR_EMPTY_EXT);
        }

        $this->min_size = $min_size;
        $this->max_size = $max_size;
    }

    /**
     * 上传文件
	 * 
     * @param string $type 上传文件类型
     * @param string $extend_type 附加类型,用逗号间隔
     * @param string $deny_type 不允许的上传类型,用逗号间隔
     * @param int $min_size 最小大小,byte单位
     * @param int $max_size 最大大小，byte单位
     */
    public function upload ($file, $des_dir, $new_name = null)
    {
        if (!isset($_FILES[$file]))
        {
            throw new FileUploaderException(FileUploaderException::ERR_NONE_FILE);
        }

        $ext = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);

        if ((!empty($this->deny_ext_arr) && in_array($ext, $this->deny_ext_arr))
                || (!empty($this->allow_ext_arr) && !in_array($ext, $this->allow_ext_arr)))
        {
            throw new FileUploaderException(FileUploaderException::ERR_WRONG_EXT);
        }

        if ($_FILES[$file]['size'] < $this->min_size)
        {
             throw new FileUploaderException(FileUploaderException::ERR_MIN_SIZE);
        }

        if ($_FILES[$file]['size'] > $this->max_size)
        {
            throw new FileUploaderException(FileUploaderException::ERR_MAX_SIZE);
        }

        if (!is_uploaded_file($_FILES[$file]['tmp_name']))
        {
            throw new FileUploaderException(FileUploaderException::ERR_NOT_UPLOADED);
        }

        if (null == $new_name)
        {
            $new_name = 'upfile' . microtime(true) . '.' . $ext;
        }

        $des_dir = preg_replace('/[\/\\\]+$/', '', $des_dir);
        $des_file = $des_dir . '/' . $new_name;
        
        if (!@move_uploaded_file($_FILES[$file]['tmp_name'], $des_file))
        {
            throw new FileUploaderException(FileUploaderException::ERR_MOVE);
        }

        return array(
            'dir'			=> $des_dir,
            'file_name'		=> $new_name,
            'full_path'		=> $des_file,
			'mime_type'		=> $_FILES[$file]['type'],
            'extension'		=> $ext,
			'size'			=> $_FILES[$file]['size']
        );
    }
}
