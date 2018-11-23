<?php

namespace Demo\Phptools;

class Tools {

    //超时时间
    public static $timeout = 5;
    public static $timeoutms = 0;
    //是否抓取跳转后的页面
    public static $getmove = 0;
    //执行curl后返回的信息
    public static $getinfo = array('http_code'=>200);
    //浏览器声明
    public static $userAgent = 'Mozilla/4.0';

    /**
     * 发送POST请求
     *
     * @param string $url            
     * @param array $param            
     */
    public static function post($url, $param = NULL) {
        $curl = curl_init ();
        curl_setopt ( $curl, CURLOPT_URL, $url );
        curl_setopt ( $curl, CURLOPT_POST, 1 );
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $param );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 1); //发起连接前等待的时间,最长1秒
        curl_setopt ( $curl, CURLOPT_TIMEOUT, self::$timeout );
        curl_setopt ( $curl, CURLOPT_USERAGENT, self::$userAgent );
        $result = curl_exec ( $curl );
        $res_info = self::$getinfo = curl_getinfo($curl);
        curl_close( $curl );
        return $result;
    }


    /**
     * CURL请求检查服务器文件是否存在
     * @param $url
     * @return bool
     */
    public static function check_remote_file_exists($url)
    {
        $curl = curl_init($url);
        // 不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); //不加这个会返回403，加了才返回正确的200，原因不明
        // 发送请求
        $result = curl_exec($curl);
        $found = false;
        // 如果请求没有发送失败
        if ($result !== false)
        {
            // 再检查http响应码是否为200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200)
            {
                $found = true;
            }
        }
        curl_close($curl);
        return $found;
    }


    // 二位数组去重
    public static function array_unique_fb($array2D){

        foreach ($array2D as $v){
            $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[]=$v;
        }
        $temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
        foreach ($temp as $k => $v){
            $temp[$k]=explode(',',$v); //再将拆开的数组重新组装
        }
        return $temp;
    }

    /**
     * GET请求某个页面
     *
     * @param string $url            
     * @param array $param            
     */
    public static function get($url, $param = NULL) {
        if (is_array ( $param )) {
            $param = http_build_query ( $param );
            if (preg_match ( '/\?/', $url )) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= $param;
        }
        $curl = curl_init ();
        curl_setopt ( $curl, CURLOPT_URL, $url );
        curl_setopt ( $curl, CURLOPT_HEADER, 0 );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 1); //发起连接前等待的时间,最长1秒
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 5 );//获取时长改为5秒
        curl_setopt ( $curl, CURLOPT_USERAGENT, self::$userAgent );
        if(self::$getmove){
            curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION,1); //是否抓取跳转后的页面
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $result = curl_exec ( $curl );
        $res_info = self::$getinfo = curl_getinfo($curl);
     
        curl_close( $curl );
        return $result;
    }

    /**
     * 获取用户的设备类型
     * @param string $agent
     * @return string "Android", "iPhone", "iPod", "iPad", "Windows Phone", "MQQBrowser", "Windows NT", "Macintosh"
     */
    public static function getDevice($agent = '') {
        if (empty($agent)) {
            $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknow';
        }
        $ary = array("Android", "iPhone", "iPod", "iPad", "Windows Phone", "MQQBrowser", "Windows NT", "Macintosh");
        $device = "unknow";
        foreach ($ary as $key) {
            if (stripos($agent, $key) > -1) {
                $device = $key;
                break;
            }
        }
        return $device;
    }

    /**
     * 获取用户的操作系统名称
     * @param string $agent
     */
    public static function getSystem()
    {
        if(isset($_SERVER['HTTP_USER_AGENT']) ){
        $sys = $_SERVER['HTTP_USER_AGENT'];
        if(stripos($sys, "NT 6.1"))
        $os = "Windows 7";
        elseif(stripos($sys, "NT 6.0"))
        $os = "Windows Vista";
        elseif(stripos($sys, "NT 5.1"))
        $os = "Windows XP";
        elseif(stripos($sys, "NT 5.2"))
        $os = "Windows Server 2003";
        elseif(stripos($sys, "NT 5"))
        $os = "Windows 2000";
        elseif(stripos($sys, "NT 4.9"))
        $os = "Windows ME";
        elseif(stripos($sys, "NT 4"))
        $os = "Windows NT 4.0";
        elseif(stripos($sys, "98"))
        $os = "Windows 98";
        elseif(stripos($sys, "95"))
        $os = "Windows 95";
        elseif(stripos($sys, "Mac"))
        $os = "Mac";
        elseif(stripos($sys, "Linux"))
        $os = "Linux";
        elseif(stripos($sys, "Unix"))
        $os = "Unix";
        elseif(stripos($sys, "FreeBSD"))
        $os = "FreeBSD";
        elseif(stripos($sys, "SunOS"))
        $os = "SunOS";
        elseif(stripos($sys, "BeOS"))
        $os = "BeOS";
        elseif(stripos($sys, "OS/2"))
        $os = "OS/2";
        elseif(stripos($sys, "PC"))
        $os = "Macintosh";
        elseif(stripos($sys, "AIX"))
        $os = "AIX";
        else
            $os = "未知操作系统";
    
        return $os;            
    }else{
        return "未知操作系统";  
    }

    }
    
    /**
     * 判断是否是wap访问
     * @return number $is_wap
     */
    public static function getIsWap() {
        $is_wap = 0;
        if(isset($_SERVER['HTTP_VIA']) && strpos($_SERVER['HTTP_VIA'],"wap")>0){
            $is_wap = 1;
        }elseif (isset( $_SERVER['HTTP_ACCEPT'] ) && ((!empty($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP") > 0) || strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"UC/") > 0 ) ){
            $is_wap = 1;
        }else {
            if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
                $iUSER_AGENT=strtolower (trim($_SERVER['HTTP_USER_AGENT']));
                if(preg_match('/(blackberry|configuration\/cldc|hp|hp-|htc|htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera|Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda|xda_)/i', $iUSER_AGENT)){
                    $is_wap = 1;
                }
            }
        }
         
        return $is_wap;
    }
    
    
    /**
     * 判断来自哪个端
     * @return string  pc  wap
     * 
     */
    public static function getVisitType() {
        $type = self::getIsWap();
        if ($type == 1) {
            return '2';
        }
        
        return '1';
    }

	/**
     * 将所有字符改为小写
     * @param unknown $str
     * @return unknown|string
     */
    public static function wordToLower( $str ) {
    
       $result =  strtolower($str);
    
        return $result;
    }

    /**
     * 处理特殊化标签
     * @param unknown $params
     */
    public static function handleSpecialTag($params) {
        if( !empty($params) ){
            if( is_array($params) ){
                foreach( $params as &$v){
                    if( is_string($v) ){
                        $v = htmlspecialchars($v);
                    }
                }
            }else{
                if( is_string($params) ){
                    $params = htmlspecialchars($params);
                }
            }
            return $params;
        }
    }

    /**
     * 格式化时间
     */
    public static function formattime($time, $format='Y-m-d') {
        $newdate = date($format, $time);
        return $newdate;
    }
    
    /**
     * 处理掉换行和回车符号
     * @param unknown $params
     */
    public static function handleNR($params) {
        if( !empty($params) ){
            if( is_array($params) ){
                foreach( $params as &$v){
                    if( is_string($v) ){
                        $v = str_replace("/n", '', $v);
                        $v = str_replace("/r", '', $v);
                    }
                }
            }else{
                if( is_string($params) ){
                    $params = str_replace("/n", '', $params);
                    $params = str_replace("/r", '', $params);
                }
            }
            return $params;
        }
    }

     /**
     * 将秒数转换为时分秒，格式为 3600*34+122  转化为34：02：02
     * @param unknown $seconds
     * @return string
     */
    public static function changeSecondsFormat($seconds){
        if ($seconds>3600){
            $hours = intval($seconds/3600);
            $minutes = $seconds/600;
            $time = $hours.":".gmstrftime('%M:%S', $minutes);
        }else{
            $time = gmstrftime('%H:%M:%S', $seconds);
        }
        return $time;
    }
    
    /**
     * 将秒转换为小时分
     * @param $second 秒数
     * @return string 小时/分
     */
    public static function secondToHour( $second ) {
        $second = intval( $second );
        if( !$second ) {
            return '0';
        }
        $string = '';
        if( $second >= 3600 ) {
            $hour = floor( $second / 3600 );
            $string .= $hour.'小时';
            $second = $second % 3600;
        }
        $minute = ceil( $second / 60 );
        $string .= $minute.'分';
        return $string;
    }

     /**
     * 检查一维数组中的值为数字，并过滤掉非数字
     * @param array $array
     */
    public static function formatNumber( $array = array() ) {
    
        if( !is_array( $array ) || empty( $array ) ) {
            return $array;
        }
    
        $_array = array();
        foreach( $array as $val ) {
            $val = intval( $val );
            if( $val > 0 ) {
                $_array[] = $val;
            }
        }
    
        return $_array;
    
    }

    /**
     * 验证并格式化日期
     * 
     * @param string $string
     */
    public static function checkDate( $string, $default = '' ) {
        
        if( empty( $string ) ) {
            return $default;
        }
        $fromdates = explode('-', $string);
        if( count($fromdates) != 3 || !checkdate($fromdates[1], $fromdates[2], $fromdates[0]) ) {
            return $default;
        } else {
            return date('Y-m-d', strtotime($string));
        }
        
    }

    /**
     * 截取中文字符或者英文字符或者数字数据
     * @param unknown $note
     * @param number $len
     * @param string $ext
     */
    public static function subWords( $str, $max_len=10, $ext='...' ) {
        $return = '';
        //计算字符长度
        $str_len = strlen($str);
        if( $str_len > $max_len){
            //截取字符串
            $jiequ_str = mb_substr($str, 0, $max_len, 'utf8');
            $return .= $jiequ_str.$ext;
        }else{
            $return .= $str;
        }
        return $return;
    }

     /**
     * 过滤P标签
     * @param unknown $content
     */
    public static function filterP( $content ) {
        //正则过滤P标签<p></p>
        $content = preg_replace("/<\/?p\s*>/","",$content);
        //过滤br标签
        $content = preg_replace("/<br\s*\/?>/","",$content);
        return $content;
    }

    /**
     * 过滤Script标签
     * @param unknown $content
     */
    public static function filterScript( $content ) {
        //正则过滤P标签<p></p>
        $content = preg_replace("/<\/?script>/","",$content);
        return $content;
    }

    /**
     * 匹配mysql中的varchar字段的字符计算方法
     * @param unknown $content
     */
    public static function strlen_match_varchar( $str ) {
        header('Content-type:text/html;charset=utf-8');
        //参考http://developer.51cto.com/art/201105/263103.htm
        //利用这两个函数则可以联合计算出一个中英文混排的串的占位是多少（一个中文字符的占位是2，英文字符是1）
        //编程里desc为varchar(1024)字符：对于varchar类型的字段，把汉字当1个字符，把英文当1个字符，把数字也当1个字符，特殊字符也当1个字符
        //在mb_strlen计算时，选定内码为UTF8，则会将一个中文字符当作长度1来计算，所以“中文a字1符”长度是6
        if( empty($str) ){
            $len = 0 ;
        }else{
            $len = mb_strlen($str,'UTF8') ;
        }
        return $len;
    }

    // 匹配UEditor中可见字符的长度
    public static function strlen_match_ueditor($str) {/*{{{*/
        if (empty($str)) {
            return 0;
        }

        $str = strip_tags($str);
        $str = htmlspecialchars_decode($str);
        // return strlen($str);
        return mb_strlen($str,'UTF8');
    }

    //获取项目面包屑
    public static function getCrumbs($param = array()){
    	if(empty($param)) return false;
    	$param = array_filter($param);
    	$str = '';
    	$data = array();
    	foreach($param as $key => $value){
    		$str .= '/'.$value;
    		$data[$key][$value] = $str;
    	}
    	return $data;
    }

    /**
     * 获取头像
     */
    public static function getAvatar($picKey , $width = 0, $height = 0, $sex = 0){
    	$filename = $picKey;
    	$url = Ap_Util_Config::get('image.ini', 'image.uurl');

    	if (empty($filename)) {
    		if (!in_array($width, array(40, 80, 160))) {
    			if ($width > 80) {
    				$width = 160;
    				$height = 160;
    			} else {
    				$width = 80;
    				$height = 80;
    			}
    		}
    		 
    		if ($sex == 1) {
    			$filename = 'images/man-'.$width.'.png';
    		} elseif ($sex == 2) {
    			$filename = 'images/girl-'.$width.'.png';
    		} else {
    			$filename = 'images/unknow-'.$width.'.png';
    		}
    		 
    		return $url. $filename;
    	}
    	
    	if ($width > 0) {
    		$filename .= '-' . $width . '-' . $height;
    	}
    	$filename .= '.' . 'jpg';
    	
    	return $url . $filename;
    }

    //生成6位随机的数字和字母混合的字符串，用于创建镜像的名字时使用
    public static function getRandString($length=6){
        $shuffleArray = array_merge(range('a', 'y'), range(3, 9));
        shuffle($shuffleArray);
        $str = implode('', array_slice($shuffleArray, 0, $length));
        return $str;
    }

	 /**
	 * 判断手机浏览还是web浏览
	 */
	public static function isMobile(){  
	    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';  
	    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';        
	    function CheckSubstrs($substrs,$text){  
	        foreach($substrs as $substr)  
	            if(false!==strpos($text,$substr)){  
	                return true;  
	            }  
	            return false;  
	    }
	    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
	    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');  

	    $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||  
	              CheckSubstrs($mobile_token_list,$useragent);  

	    if ($found_mobile){  
	        return true;  
	    }else{  
	        return false;  
	    }  
	}


	/**
	 * 计算俩个日期的差，返回年，月，天
	 * @param  [type] $date1 [description]
	 * @param  [type] $date2 [description]
	 * @return [type]        [description]
	 */
	public static function diffDate($date1,$date2){ 
		$datestart = date('Y-m-d',strtotime($date1));
	    if(strtotime($datestart)>strtotime($date2)){ 
	        $tmp = $date2; 
	        $date2 = $datestart; 
	        $datestart = $tmp; 
	    } 
	    list($Y1,$m1,$d1) = explode('-',$datestart); 
	    list($Y2,$m2,$d2) = explode('-',$date2); 
	    $Y = $Y2-$Y1; // 1
	    $m = $m2-$m1; // 0
	    $d = $d2-$d1; // -11
	    if($d<0){ 
	        $d += (int)date('t',strtotime("-1 month $date2")); 
	        $m = $m--; 
	    } 
	    if($m < 0){ 
	        $m += 12; 
	        $y = $y--; 
	    } 
	    if($Y == 0 && $m == 0 && $d != 0){
			return $d.'天';
	    }elseif($Y == 0 && $m != 0 && $d != 0){
	    	return $m.'个月'.$d.'天';
	    }elseif($Y != 0 && $m == 0 && $d != 0){
	    	return $Y.'年'.$d.'天';
	    }else{
	    	return $Y.'年'.$m.'个月'.$d.'天';
	    }
	} 


    //功能：计算两个时间戳之间相差的日时分秒
    //$begin_time  开始时间戳
    //$end_time 结束时间戳
    public static function timediff($begin_time,$end_time)
    {
          if($begin_time < $end_time){
             $starttime = $begin_time;
             $endtime = $end_time;
          }else{
             $starttime = $end_time;
             $endtime = $begin_time;
          }

          //计算天数
          $timediff = $endtime-$starttime;
          $days = intval($timediff/86400);
          //计算小时数
          $remain = $timediff%86400;
          $hours = intval($remain/3600);
          //计算分钟数
          $remain = $remain%3600;
          $mins = intval($remain/60);
          //计算秒数
          $secs = $remain%60;
          $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
          return $res;
    } 


    /**
     * 下载图片并且保存到制定文件夹
     * @param $url 下载地址 $user_token保存文件名 $filePath保存目录
     */
    public static function downloadImage($url, $user_token, $filePath)
    {      
        $file = self::get($url);
        $filename=$user_token.".jpg";
        $path=$filePath.$filename;
        self::saveAsImage($file, $path);
    }    
    private  static function saveAsImage($file, $path)
    {
        $resource = fopen($path, 'a');
        fwrite($resource, $file);
        fclose($resource);
    }


    /**
     * 防sql注入,xss攻击
     */
    public static function clean($str)
    {
        $str = trim($str);        
        $str = strip_tags($str);        
        $str = stripslashes($str);        
        $str = addslashes($str);        
        $str = rawurldecode($str);        
        $str = quotemeta($str);        
        $str = htmlspecialchars($str);        
        return $str;
    }


    /**
     * 生成6位数验证码
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    public static function random_code($length = 6)
    {
        $code = rand(pow(10, ($length - 1)), pow(10, $length) - 1);
        return "$code";
    }


    /**
     * 产生指定长度的字母和数字字符串（有重复的可能性）
     * @param  [type] $length [description]
     * @return [type]         [description]
     */
    public static function randomkeys($length)
    {
        $returnStr = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for ($i = 0; $i < $length; $i++) {
        $returnStr .= $pattern{mt_rand(0, 61)}; //生成php随机数
        }
        return $returnStr;
    }


    /**
     * 生成订单详情号 默认为16位
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    public static function get_order_id($length = 16)
    {
        //见方法一，获得毫秒级别时间戳
        $time = self::get_millisecond();
        $len = $length - 13;
        $str = self::get_nonce_number($len);
        if (strlen($time) != 13) {
            $orderId = $str . $time . rand(1, 9);
        } else {
            $orderId = $str . $time;
        }
        return $orderId;
     
    }


    /**
     * 产生随机字符串，不长于32位
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    public static function get_nonce_number($length = 11)
    {
        $chars = "0123456789";
        $str = "";        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


    /**
     * 获得毫秒级别的时间戳
     * @return [type] [description]
     */
    public static function get_millisecond()
    {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time = $time[1] . substr($time[0], 2, 3);
        return $time;
    }


    /**
     * 读取文件内容
     */
    public static function file_read($file_path, $readSize)
    {
        if (file_exists($file_path)) {
            $fp = fopen($file_path, "r");
            $str = fread($fp, $readSize);
            $str = str_replace("\r\n", "<br />", $str);
            fclose($fp);
            return $str;
        } else {
            return false;
        }
    }

    /**
     * 查小于$num的数字中最大的数字。
     */
    public static function search( $num_list, $num )
    {
        $l = count( $num_list );
        for( $i=0; $i<$l; $i++ ) {
            if ( $num_list[$i] >= $num ) {
                break;
            }
        }
        return $i == 0 ? false : $num_list[$i-1];
    }

/**
 * clas end
 */
}