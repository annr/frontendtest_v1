<?php

namespace Ft\HomeBundle\FtRequest;

class FtHelper
{
	
    public static function testHttpCode($ft_http_request)
	{
		global $ft_url;
		$url = $ft_url;
		if(!($ft_http_request['http_code'] == '200')) {
			echo 'HTTP RESPONSE CODE OTHER THAN 200 FOR: '.$url . "\n\rexiting....";
			error_log('HTTP RESPONSE CODE OTHER THAN 200 FOR: '.$url);
			exit;			
		} 
	}
	
    public static function testContentType($ft_http_request)
	{
		global $ft_url;
		$url = $ft_url;
		if($ft_http_request['content_type'] != 'text/html' && $ft_http_request['content_type'] != 'application/xhtml+xml') {
			echo 'NOT A SUPPORTED CONTENT TYPE ('.$ft_http_request['content_type'].'): '.$url . "\n\rexiting....";
			error_log('NOT A SUPPORTED CONTENT TYPE ('.$ft_http_request['content_type'].'): '.$url);
			exit;			
		}
	}

    public static function testMinContentLength($ft_http_request,$min_length=1000)
	{	
		global $ft_url;
		$url = $ft_url;
		if(intval($ft_http_request['download_content_length']) < $min_length ) {
			echo "HTTP RESPONSE HAS VERY LITTLE CODE. NOT MUCH TO TEST? (".$url . ")\n\rexiting....";
			error_log('HTTP RESPONSE HAS VERY LITTLE CODE. NOT MUCH TO TEST? ('.$url . ')');
			exit;			
		} 
	}
	
    public static function setFtData($url)
	{
		return FtHelper::getDataAndSetRequest($url);		
	}
	
    public static function setFtDom($url)
	{
		global $ft_data;
		global $ft_dom;
		
		$ft_dom = new \DomDocument();
		$ft_dom->preserveWhiteSpace = true;

		@$ft_dom->loadHTML($ft_data);
	}
	

    public static function setMiscFtGlobals($request_header)
	{
		global $ft_host;
		global $ft_get;
		global $ft_url_root;
		global $ft_web_root;
		
		$http_request_split = explode("\n", $request_header);
		$get_split = explode(" ", $http_request_split[0]);
		$host_split = explode(" ", $http_request_split[1]);
		
		$ft_host = trim($host_split[1]);
		$ft_get = $get_split[1];
		$protocol = explode('/',$get_split[2]);	
		$ft_url_root = strtolower($protocol[0]) . '://' . $ft_host . substr($ft_get, 0, (strrpos($ft_get, '/') + 1));
		
		$ft_web_root = strtolower($protocol[0]) . '://' . $ft_host . '/';
				
	}
		
    public static function getDataAndSetRequest($url,$print_request_headers = 0)
	{	
		global $ft_http_request;
		
		 $ch = \curl_init();	
		 \curl_setopt($ch, CURLOPT_URL,$url);
		 \curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		 \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		 \curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		 \curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		 $data = \curl_exec($ch);

		if(!\curl_errno($ch))
		{
		 	$ft_http_request = \curl_getinfo($ch);
			if($print_request_headers) {
				foreach($ft_http_request as $key => $value){
				  echo $key.":" . $value . "<br>\r\n";
				}	
			}		
		} else {
			error_log('CURL ERROR WITH URL: '.$url. ' in ' . __FILE__ . " " . __LINE__);
		}

		\curl_close($ch);
		
		//I realize it's weird to set one global var and return another. 
		return $data;		
	}

}