<?php

namespace Ft\CoreBundle\CoreTest;

class Helper
{

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
	
	
    public static function getAbsoluteResourceLink($page_link)
    {
    	global $ft_url_root;
    	global $ft_web_root;
    	
    	$link = $page_link;
    	
		if (substr($link, 0, 7) === 'http://' || substr($link, 0, 8) === 'https://') { 											
			//link is fine, do not modify
		}
		//if forward slash, add url minus forward slash.
		elseif (substr($link, 0, 1) === '/') { 
			$link = $ft_web_root.substr($link, 1); 
		}
		//if relative, add url making sure forward slash exists
		elseif (substr($link, 0, 1) !== '/') { 
			echo 'adding ft_url_root? ' . $ft_url_root;
			$link = $ft_url_root.$link; 
		}
				
		return $link;
    }

    public static function getHttpResponseCode($link)
    {
		$handle = curl_init($link);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$info = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        var_dump($info);
		curl_close($handle);			
		return $info;
	}
	
	public static function getResourceSizeBytes($link)
    {
		$handle = curl_init($link);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$info = curl_getinfo($handle, CURLINFO_SIZE_DOWNLOAD);

		curl_close($handle);
		
		return $info;
	}

	public static function printCodeWithLineNumber($element)
    {	
		//there is an issue with this code. because an element may have had linebreaks removed, it might not be matched. 
		//therefore we must try to match the substring before any line breaks. this means that an element may not be accurately matched to it's line. 
		//fixed it as well as I can for now, and I will not show line number if it can't be matched (and output to error log)

		global $ft_data;
		global $ft_request_id;
		$doc=new \DOMDocument();
		$doc->preserveWhiteSpace = true; 
		$doc->appendChild($doc->importNode($element,true));
		$code_str = $doc->saveHTML();

		//get line breaks previous to $meta
		$code_str = trim($code_str);
		$element_pos = stripos($ft_data, $code_str);
		$text = substr($ft_data, 0, stripos($ft_data, $code_str));

		if(strpos($code_str,"\n") > 0)
		{
			$code_str = substr($code_str, 0, strpos($code_str,"\n"));
		}
		if($text == null)
		{
			//try to get it again with chopped string:
			$text = substr($ft_data, 0, stripos($ft_data, $code_str));
		}

		if($text) {
			$line = 1; //the first line is one.
			$line += substr_count($text, "\n");
			//echo '<br>finding line no: ' . $line;
			$code =  '`('. $line . ') '. $code_str . '`' . "  \n\r";
		} else {
			//line number not found, so don't print it.
			$code =  '`'.$code_str . '`' . "  \n\r";	
			error_log('FT ERROR with request id ' . $ft_request_id . ': DOM ELEMENT NOT FOUND IN RAW SOURCE '.$code_str);		
		}
		return $code;
	}
	
	public static function testForElement($element_str)
	{	
		global $ft_dom;
			
		$code = array('');
	
		$elements = $ft_dom->getElementsByTagName($element_str);

		if($elements->length == 0) {
			return false;
		} else {
		    foreach ($elements as $element) { 
				$code[0] .=  Helper::printCodeWithLineNumber($element);						
			}	
		}
		
		if($code[0] != '') {
			return $code;
		}		
		
        return false;
	}

/*	
	public function recursiveSearch( $node ) {			
	   if ($node->hasAttribute($attribute_name) !== false)
	   if ( $node->hasChildNodes() ) {
	     $children = $node->childNodes;
	     foreach( $children as $kid ) {
	       if ( $kid->nodeType == XML_ELEMENT_NODE ) {
	         $this->getHtml5ClassElement( $kid,$html5_elements );
	       }
	     }
	   }
	}
	*/
	public static function recursivelySearchAttribute( $node, $attribute_name ) {
	   global $poorly_designed_catchall;
	   global $poorly_designed_catchall_element_array;
				
	   if ($node->hasAttribute($attribute_name) !== false) { 
			$poorly_designed_catchall++; 
			$poorly_designed_catchall_element_array[] = $node;
	   }
	   if ( $node->hasChildNodes() ) {
	     $children = $node->childNodes;
	     foreach( $children as $kid ) {
	       if ( $kid->nodeType == XML_ELEMENT_NODE ) {
	         Helper::recursivelySearchAttribute( $kid,$attribute_name );
	       }
	     }
	   }
	}	

	public static function recursivelySearchAttributeValue( $node, $attribute_name, $attribute_value ) {
	   global $poorly_designed_catchall;
	   global $poorly_designed_catchall_element_array;
				
	   if ($node->hasAttribute($attribute_name) !== false && $node->getAttribute($attribute_name) == $attribute_value) { 
			$poorly_designed_catchall++; 
			$poorly_designed_catchall_element_array[] = $node;
	   }
	   if ( $node->hasChildNodes() ) {
	     $children = $node->childNodes;
	     foreach( $children as $kid ) {
	       if ( $kid->nodeType == XML_ELEMENT_NODE ) {
	         Helper::recursivelySearchAttributeValue( $kid,$attribute_name, $attribute_value  );
	       }
	     }
	   }
	}	
		
}