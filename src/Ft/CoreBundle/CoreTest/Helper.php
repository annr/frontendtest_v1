<?php

namespace Ft\CoreBundle\CoreTest;

class Helper
{

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
		
		//I realize it's weird to set one global var and return another. 
		return $data;		
	}
	
	
    public static function getAbsoluteResourceLink($page_link)
    {
    	global $ft_url_root;
    	global $ft_web_root;
    	
    	$link = $page_link;
    	
		if(substr($link, 0, 2) === '//') {
			//add the http protocol. this might not be perfect.
			$link = 'http:'.$link;
		} elseif (substr($link, 0, 2) === '//' || substr($link, 0, 7) === 'http://' || substr($link, 0, 8) === 'https://') { 											
			//link is fine, do not modify			
		} elseif (substr($link, 0, 1) === '/') { 
			//if forward slash, add url minus forward slash.
			$link = $ft_web_root.substr($link, 1); 
		} elseif (substr($link, 0, 1) !== '/') { 
			//if relative, add url making sure forward slash exists
			$link = $ft_url_root.$link; 
		}
				
		return $link;
    }

	public static function isLocalFile($page_link) {
		//FUNCTION NOT DONE!!!		
		if (substr($page_link, 0, 2) === '//' || substr($page_link, 0, 7) === 'http://' || substr($page_link, 0, 8) === 'https://' ) { 
			return false;
		}	
		return true;
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
		\curl_close($ch);
		
		//how big is data? If we just get a bit of it, we should be able to tell if it's minified or not. 
		//sometime the top is minified and the bottom is not. 
		$sample_chunk_data = substr($data,0,2000);
		
		//find linebreaks, whitespace and tabs in data:
		if(substr_count($sample_chunk_data, "\n") > 10 || substr_count($sample_chunk_data, "\r") > 10)
		{
			return false;
		}
		
		//the threshold is very easy: it's either minified or not. 
		//however, the percent that can be saved should be shown to the user as well. 
		//this is an important to-do.		
	
		return true;
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
		$doc->formatOutput = false; 
		$doc->appendChild($doc->importNode($element,true));
		$code_str = trim($doc->saveHTML());

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
		$unbrokencharspans = explode(' ',$code_str);
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
				$code =  '`('. $line . ') '. $code_str . '`' . "  \n\r";				
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
				
	   if ($node->hasAttribute($attribute_name) !== false) { 
			if(in_array($node->getAttribute($attribute_name),$poorly_designed_catchall)) {
				$poorly_designed_catchall_element_array[] = $node->getAttribute($attribute_name);
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

		$data_without_comments = Helper::removeCommentsFromString($ft_data);

		if(strpos(trim(strtolower($data_without_comments)), '<!doctype') === false || strpos(trim(strtolower($data_without_comments)), '<!doctype') != 0) 
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

	
	
	
	
		
		
}