<?php

/**
 * 网络实用工具
 */
class NetUtils
{

    /**
     * 取客户端的真实IP
     *
     * @return stirng ture ip
     */
    static public function get_client_ip ()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
        {
            $onlineip = getenv('HTTP_CLIENT_IP');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
        {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
        {
            $onlineip = getenv('REMOTE_ADDR');
        }
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
        {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        $onlineip = preg_replace("/^([d.]+).*/", "1", $onlineip);
        return $onlineip;
    }

    static public function get_client_ip_long ()
    {
        return vsprintf('%u', ip2long(self::get_client_ip()));
    }

    /**
     * 以一定的速率发送文件
     * 用于客户下载文件
     *
     * @param string $file_name
     * @param string $byte_rate 单位K， 默认为0，全速下载
     * @return bool
     */
    static public function send_file ($file_name, $byte_rate = 0)
    {
        if (!is_file($file_name))
            throw new Exception('不是文件');

        if (!is_readable($file_name))
            throw new Exception('无权读取');

        if (headers_sent())
            throw new Exception('Header已经发出');

        $fp = fopen($file_name, 'r');
        if (!$fp)
            throw new Exception('无法打开文件');

        header("Expires: 0");
        header("Pragma: no-cache");
        header('Content-Type: application/x-octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Length: ' . filesize($file_name));

        $sleep_time = (0 >= $byte_rate) ? 0 : intval(1000000 / $byte_rate);

        while (!feof($fp))
        {
            echo fread($fp, 1024);
            usleep($sleep_time);
        }

        fclose($fp);

        return true;
    }

    /**
     * 将子网掩码十六进制转化成十进制的形式
     *
     * @param string $netmask
     * @return string
     */
    static public function netmask_hex2dec ($netmask)
    {
        $netmask = (substr($netmask, 0, 2) == "0x") ? substr($netmask, 2) : $netmask;
        $rs = "";
        for ($i = 0; $i < 8;)
        {
            $sub = substr($netmask, $i, 2);
            $rs.=hexdec($sub) . ".";
            $i = $i + 2;
        }
        $rs = substr($rs, 0, strlen($rs) - 1);
        return $rs;
    }

    /**
     * 根据IP和子网掩码计算网段
     *
     * @param string $ip
     * @param string $netmask
     * @return string
     */
    static public function generate_subnet ($ip, $netmask)
    {
        //转成二进制
        $ipBin = decbin(ip2long($ip));
        while (strlen($ipBin) < 32)
        {
            $ipBin = '0' . $ipBin;
        }
        $netmaskBin = decbin(ip2long($netmask));

        //网段二进制形式
        $bitIndex = 0;
        $subnetBin = '';
        while ($bitIndex < 32)
        {
            if ($netmaskBin [$bitIndex] == 1)
            {
                $subnetBin .= $ipBin [$bitIndex];
            }
            else
            {
                $subnetBin .= '0';
            }
            $bitIndex++;
        }

        //网段
        $subnet = long2ip(bindec($subnetBin));
        return $subnet;
    }

    /**
     * 根据IP和子网掩码计算广播地址
     *
     * @param string $ip
     * @param string $netmask
     * @return string
     */
    static public function generate_broadcast_addr ($ip, $netmask)
    {
        //转成二进制
        $ipBin = decbin(ip2long($ip));
        while (strlen($ipBin) < 32)
        {
            $ipBin = '0' . $ipBin;
        }
        $netmaskBin = decbin(ip2long($netmask));

        //广播地址二进制形式
        $bitIndex = 0;
        $BroadcastAddrBin = '';
        while ($bitIndex < 32)
        {
            if ($netmaskBin [$bitIndex] == 1)
            {
                $BroadcastAddrBin .= $ipBin [$bitIndex];
            }
            else
            {
                $BroadcastAddrBin .= '1';
            }
            $bitIndex++;
        }

        //广播地址
        $BroadcastAddr = long2ip(bindec($BroadcastAddrBin));
        return $BroadcastAddr;
    }

}