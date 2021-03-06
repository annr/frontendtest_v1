<?php

namespace Ft\CoreBundle\CoreTest;

class Helper
{
	public static $max_disp_threshold = 4;	

    public static function getDataAndSetRequest($url,$print_request_headers = 0)
	{	
		global $ft_http_request;
		global $ft_curl_getinfo;
		
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
		//remove the BOM
		$data = preg_replace('/\x{EF}\x{BB}\x{BF}/','',$data); 
		
		//I realize it's weird to set one global var and return another. 
		return $data;		
	}
	
	
    public static function getAbsoluteResourceLink($page_link)
    {
    	global $ft_url_root;
    	global $ft_web_root;
    			
		//replace spaces with %20.
		//what other ways do we need to encode the URL so that we can test the link?
		$link = str_replace(' ','%20',$page_link);
    	
		if(substr($link, 0, 2) === '//') {
			//add the http protocol. this might not be perfect.
			$link = 'http:'.$link;
		} elseif (substr($link, 0, 2) === '//' || substr($link, 0, 7) === 'http://' || substr($link, 0, 8) === 'https://') { 											
			//link is fine, do not modify			
		} elseif (substr($link, 0, 1) === '/') { 
			//if forward slash, add url minus forward slash.
			$link = $ft_web_root.substr($link, 1); 
		} elseif (substr($link, 0, 2) === './') { 
			//if dot forward slash, add url minus dot forward slash.
			$link = $ft_web_root.substr($link, 2);
		} elseif (substr($link, 0, 3) === '../') { 
			$new_root = $ft_url_root;
			for($i = 0; $i < substr_count($link,'../'); $i++) {
				///use root.
				//trim url from last or second to last forward slash to end.
				//remove folders from url root, and shift relative
				$new_root = substr($new_root, 0, strrpos($new_root,'/',-2) + 1);
				//trim first:
				$link = substr($link, 3);				
			}		
			$link = $new_root.$link; 
		} elseif (substr($link, 0, 1) !== '/') { 
			//if relative, add url making sure forward slash exists
			$link = $ft_url_root.$link; 
		}
				
		return $link;
    }

    public static function hasInlineStyleAttribute($element, $attribute)
    {
		if($element->hasAttribute('style')) {
			if (stripos($element->getAttribute('style'),$attribute)) { return true; }			
		}
		return false;
	}
	
		
    public static function checkElementHref($element)
    {
		//check link status
		if ($element->hasAttribute('href')) {
			//link to check:
			$link = $element->getAttribute('href');
		
			//if matches url, skip TODO
			if($link == './' || $link == '#') {
				return false;					
			}
		
			//also if using javascript: protocol or the mailto:, skip.}
			if(strpos($element->getAttribute('href'),'javascript:') !== false || strpos($element->getAttribute('href'),'mailto:') !== false){
				return false;
			}
							
			$link = Helper::getAbsoluteResourceLink($link);

			if(Helper::getHttpResponseCode($link) == 404) {
			    /* Handle 404 here. */
				return true;
			    //$code[0] .=  Helper::printCodeWithLineNumber($element);
			}
			return false;										
		}
	}

    public static function http200Test($url)
	{
		$headers = @get_headers($url, 1);
		if(!$headers) { return false;}
		$header_str = explode(' ',$headers[0]);	
		if($header_str[1] == '200') {
			return true;
		}
		return false;		
	}
	
	public static function httpHtmlTypeTest($url)
	{
		$headers = @get_headers($url, 1);
		//this is either a string or an array!!!! 
		//error_log(gettype($headers["Content-Type"]));
		//var_dump($headers["Content-Type"]);
		
		//if get headers returns false, you can't test. 
		if(!$headers) { return false;}

		if(gettype($headers["Content-Type"]) == 'string' && strpos($headers["Content-Type"],'text/html') !== false) {
			return true;
		} elseif (gettype($headers["Content-Type"]) == 'array' && strpos($headers["Content-Type"][0],'text/html') !== false) {			
			return true;
		}
		return false;		
	}

    public static function httpBadStatusCode($url)
	{
		$headers = get_headers($url, 1);
		
		//not being able to get headers is an indication (to me!) that it has a bad status code. 
		//it's the case with http://mdch.at/lG4hzm linked from http://mdchat.org/		
		if(!$headers) { return true; }
				
		$header_str = explode(' ',$headers[0]);
		$bad_codes = array('400','404','408','410');
		
		if(in_array($header_str[1],$bad_codes)) {
			//var_dump($header_str);
			//echo $url;
			
			//this extra test is required for certain web sites that do not want their content spidered.
			//it won't be easy to catch all of these kinds of cases, but the specific case I'm fixing:
			// www.mdchat.org with URLs such as
			// http://www.scribd.com/embeds/48466709/content?start_page=1&view_mode=list&access_key=key-1ocsvs7iik05cs4ry0lh
			//is handled in this case
			$handle = curl_init($url);
			curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0");
			/* Get the HTML or whatever is linked in $url. */
			$response = curl_exec($handle);
			$info = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			//echo "INFO: " . $info;

			curl_close($handle);
			//var_dump($response);
			if(in_array($info,$bad_codes)) {
				return true;
		    }
		}	
		return false;		
	}
		
	public static function isLocalFile($page_link) {
		//FUNCTION NOT DONE!!!		
		
		//a local file starts with a single slash, a word, a period.
		if (substr($page_link, 0, 2) === '//' || substr($page_link, 0, 7) === 'http://' || substr($page_link, 0, 8) === 'https://' ) { 
			return false;
		}	
		return true;
	}
	
	public static function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}
	

    public static function getHttpResponseCode($link)
    {
		$handle = curl_init($link);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);
		$info = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		curl_close($handle);			
		return $info;
	}
	
	public static function getResourceSizeBytes($link)
    {
		$handle = curl_init($link);
		\curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		/* Get the HTML or whatever is linked in $url. */
		$response = \curl_exec($handle);
		$info = \curl_getinfo($handle, CURLINFO_SIZE_DOWNLOAD);
		\curl_close($handle);
		return $info;
	}

		
	public static function isMinified($url)
    {	
		 $ch = \curl_init();	
		 \curl_setopt($ch, CURLOPT_URL,$url);
		 \curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		 \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		 \curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		 \curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		 $data = \curl_exec($ch);
		 $info = \curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
		 \curl_close($ch);
		
		 // this script may be inefficient. we should be able to look at part of the data to see if it's minified. However, I'm doing what I
		 // think is the best test for ow.		
		 // looking at unminified vs partly minified files I've discovered this:
		 //   not minified: 5672 bytes, 509 whitespace: 509/5672 = 0.08973906911142 (.09)
		 //     
		 //   partly minified: 81131 bytes, 2674 whitespace: 2674/81131 = 0.03432719922101 (.034)
		 //		
		 // therefore we can consider a file with such a ratio as .07 to be unminified or poorly minified.
		
		 $total_whitespace = substr_count($data, ' ') + substr_count($data, "\n") + substr_count($data, "\r") + substr_count($data, "\r"); 

		 if($total_whitespace/$info > 0.07)
		 {	
	 		 return false;
		 }
		
		 //the threshold is very easy: it's either minified or not. 
		 //however, the percent that can be saved should be shown to the user as well. 
		 //this is an important to-do.		
	
		 return true;
	}
	

//	public static function printCodeWithLineNumber($element,$add_tics=true)
    public static function printCodeWithLineNumber($element)
    {	
		//there is an issue with this code. because an element may have had linebreaks removed, it might not be matched. 
		//therefore we must try to match the substring before any line breaks. this means that an element may not be accurately matched to it's line. 
		//fixed it as well as I can for now, and I will not show line number if it can't be matched (and output to error log)

		global $ft_data;
		global $ft_request_id;
		$doc=new \DOMDocument();
		$doc->preserveWhiteSpace = true; 
		$doc->formatOutput = false; 
		$doc->appendChild($doc->importNode($element,true));
		$code_str = trim($doc->saveHTML());
		
		//$tic = '`';
		//if($add_tics === false) {$tic = '';}

		//echo "\n\ncode: \n".$code_str;
		//if we can find the code in the string, great we're in business.
		// if strtolower() is too slow, we can take it off $code_str 
		$element_pos = stripos(strtolower($ft_data), strtolower($code_str));
		if($element_pos !== false) {
			$text = substr($ft_data, 0, stripos(strtolower($ft_data), strtolower($code_str)));
	    } else {
			//the first guess is that the code has XHTML end tags not matching the re-saved HTML element.
			//replace first occurance of '>' in $code_str with ' />'
			$test_code_str = str_replace('>',' />',$code_str);
			//echo "\nnew test:\n".$test_code_str;
			
			//success?
			if(stripos($ft_data, $test_code_str) !== false) {
				//replace overridden code str, because that's what's in their code. 
				$code_str = $test_code_str;
				$text = substr($ft_data, 0, stripos(strtolower($ft_data), $code_str));			
			}			
			//if text still is not set....
			//it might be that there is a linebreak in the orig html doc or some other difference from the original.
			//this is too expensive of a test to do, but the way we might do it is:			
			if(!isset($text))
			{	
				error_log('FT ERROR with request id ' . $ft_request_id . ': HELLUVA TIME MATCHING DOM ELEMENT WITH RAW SOURCE '.$code_str);	
				//there is a line break or some other char in the middle of a tag!
				//remove all whitespace from both strings and see if you can find it.
			}		
	    }
		
		if(!isset($text) && strpos($code_str,"\n") > 0)
		{
			$code_str = substr($code_str, 0, strpos($code_str,"\n"));
			$text = substr($ft_data, 0, stripos(strtolower($ft_data), $code_str));
		}
		
		//if code_str has no spaces, and is greater than x chars, add a space to break the line every x chars.		
		//determine if the test is necessary, and pass in range. this is a dangerous recursive thing!
		$pattern = '/\s|-/';
		$unbrokencharspans = preg_split($pattern,$code_str);
		foreach($unbrokencharspans as $unbrokencharspan) {
			if(strlen($unbrokencharspan) > 65) { 
				$start = strpos($code_str,$unbrokencharspan); 
				//echo "\nstart: " . $start;
				$end = $start + strlen($unbrokencharspan); 
				//echo "\end: " . $end;
				//echo "\n breaking...".$code_str ."\n\n";
				$code_str = Helper::addWhitespaceForReportFormatting($code_str,$start,$end);			
			}
		}

		if(isset($text) && $text != '') {
			$line = 1; //the first line is one.
			$line += substr_count($text, "\n");
			//when the line number is 1, it's not valuable.
			if($line != 1){
				$code =  '`'.'('. $line . ') '. $code_str . '`' . "  \n\r";				
			} else {
				$code =  '`'.$code_str . '`' . "  \n\r";					
			}
		} else {
			//line number not found, so don't print it.
			$code =  '`'.$code_str . '`' . "  \n\r";	
			//error_log('FT ERROR with request id ' . $ft_request_id . ': DOM ELEMENT NOT FOUND IN RAW SOURCE '.$code_str);		
		}
		
		return $code;

	}

	//public static function getDomainFromUrl($url)
	//{
		//preg_match('/[a-z].\.[a-z].\.[a-z]/',$url,$match);
	//}	
	
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
	
	public static function getAllDomLinks()
	{	
		global $ft_dom;
		$links_array = array();
		
		//for efficiency, just test the elements you expect to have src: script, img
		//to-do: there is also src in input type=image
		$tags_array = array('script','img');
		$attr = 'src';
        foreach ($tags_array as $tag) { 			
			$elements = $ft_dom->getElementsByTagName($tag);
	        foreach ($elements as $element) { 
				//check link status
				if ($element->hasAttribute($attr)) {
					$links_array[] = $element->getAttribute($attr);
				}
			}
		}
		
		//for efficiency, just test the elements you expect to have href: link, a
		$tags_array = array('a','link');	
		$attr = 'href';
	    foreach ($tags_array as $tag) { 			
			$elements = $ft_dom->getElementsByTagName($tag);
	        foreach ($elements as $element) { 
				//check link status
				if ($element->hasAttribute($attr)) {
					$links_array[] = $element->getAttribute($attr);
				}
			}
		}		
		return $links_array;
		
	}

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
	
	public static function recursivelyGetDuplicateAttributeValue( $node, $attribute_name ) {
	   global $poorly_designed_catchall;
	   global $poorly_designed_catchall_element_array;
	   //global $poorly_designed_catchall_instances;
				
	   if ($node->hasAttribute($attribute_name) !== false) { 
			//if it's in the collected attribute values AND not already in the catchall element array
			if(in_array($node->getAttribute($attribute_name),$poorly_designed_catchall)) {
				if(!in_array($node->getAttribute($attribute_name),$poorly_designed_catchall_element_array)) {
					$poorly_designed_catchall_element_array[] = $node->getAttribute($attribute_name);					
				} //else {
					//$poorly_designed_catchall_instances++;
				//}
			} else {
				$poorly_designed_catchall[] = $node->getAttribute($attribute_name);				
			}
	   }
	   if ( $node->hasChildNodes() ) {
	     $children = $node->childNodes;
	     foreach( $children as $kid ) {
	       if ( $kid->nodeType == XML_ELEMENT_NODE ) {
	         Helper::recursivelyGetDuplicateAttributeValue( $kid,$attribute_name );
	       }
	     }
	   }
	}	

	//this function does not work right!!! I loops over the dom many times.
	public static function recursivelySearchAttributeValue( $node, $attribute_name, $attribute_value, $tag) {
	   global $poorly_designed_catchall;
	   global $poorly_designed_catchall_element_array;

		if($tag == '' || ($tag != '' && $node->nodeName == $tag)) {
			if ($node->hasAttribute($attribute_name) !== false && $node->getAttribute($attribute_name) == $attribute_value) { 
				$poorly_designed_catchall++; 
				$poorly_designed_catchall_element_array[] = $node;
		   }
		}
						
	   if ( $node->hasChildNodes() ) {
	     $children = $node->childNodes;
	     foreach( $children as $kid ) {
	       if ( $kid->nodeType == XML_ELEMENT_NODE ) {
	         Helper::recursivelySearchAttributeValue( $kid,$attribute_name, $attribute_value, $tag );
	       }
	     }
	   }
	}
	
	public static function str_insert($insertstring, $intostring, $offset) {
	   $part1 = substr($intostring, 0, $offset);
	   $part2 = substr($intostring, $offset);

	   $part1 = $part1 . $insertstring;
	   $whole = $part1 . $part2;
	   return $whole;
	}


	public static function addWhitespaceForReportFormatting($str,$index_start=0,$index_end=null) {
		$index = $index_start;
		//maxchars would be changed if the width of the email is.
		$maxchars = 65;
		if(!isset($index_end)) {
			$index_end = strlen($str);
		}

		while($index < $index_end) 
		{
			//echo "\nindex " . $index;
			//echo "\nindex_end " . $index_end;
			$str = Helper::str_insert(' ', $str, $index + $maxchars);
			$index = $index + $maxchars;
		}		
		//echo "\nbroken str: " . $str;
		return $str;
	}

	public static function HasHtml5Doctype() 
	{
		global $ft_dom;
		if($ft_dom->doctype != null && $ft_dom->doctype->publicId == '') { return true; }
		return false;		
	}
	
	public static function DoctypeFirstElementCheck() 
	{
		global $ft_data;
		//echo substr(trim(strtolower($ft_data)),0,300);

		$data_without_comments = Helper::removeCommentsFromString($ft_data);
	
		//remove the BOM:
		//$data_without_comments = preg_replace('/\x{EF}\x{BB}\x{BF}/','',$data_without_comments);

		//what are these special chars that can be in source?
		if(stripos(trim($data_without_comments), '<!doctype ') === false || stripos(trim($data_without_comments), '<!doctype ') != 0) 
		{	
			return false;
		}
		return true;

	}
	
	public static function removeCommentsFromString($code_str)
	{	
		//what is a comment? it's <!-- then anything including -- then -->
		//also it's <!> but let's ignore that for now.
		//importantly a comment is <!-- until it gets to a close comment. this is one comment <!--<!--<!--<!-->
		
		//this seems to work
		return preg_replace('/<!--.*-->/','',$code_str);
	}
	
	public static function stringFound($str)
	{
		global $ft_data;
		return(strpos($ft_data,$str));
	}
	
	public static function countElements($element_str,$exists_optional_attr='')
	{
		global $ft_dom;
		$elements = $ft_dom->getElementsByTagName($element_str);
		$count = 0;
		if($exists_optional_attr != '') {

	       foreach ($elements as $element) { 
				if($element->hasAttribute($exists_optional_attr)) { $count++; }
			}
	
		} else {
			$count = $elements->length;
		}
		return($count);
	}
	
	public static function getBlockingScriptsInHead($head)
	{
		//exclude these scripts, for now.
		//ajax.googleapis, optimizely
		$elements = $head->getElementsByTagName('script');
		$count = 0;
		
		foreach ($elements as $element) { 
			if($element->hasAttribute('src') && strpos($element->getAttribute('src'),'ajax.googleapis') === false && strpos($element->getAttribute('src'),'optimizely') === false) { $count++; }
		} 		
		return($count);
	}
	
	public static function likelyPixel($element)
	{
		if($element->hasAttribute('width') &&  $element->getAttribute('width') == '1') { return true; }
		if($element->hasAttribute('src')) {
			$pattern = '/\.gif|\.png|\.jpg/';
			preg_match($pattern,$element->getAttribute('src'),$match);
			if(!isset($match[0])){ return true;}
		}
		return false;
	}
	

	
	
	
	
		
		
}