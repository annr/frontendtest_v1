<?php

namespace Ft\CoreBundle\CoreTest;

use Ft\CoreBundle\CoreTest\Helper;

class HTML
{

	public $poorly_designed_recursive_search_output = array('');

	public function CssOutsideOfHead()
	{
		//there are two ways this can be true. 
		//1) the link tag is used with an external css file outside of head (a rare mistake which may work in some browsers) and
		//2) style tags are found in the body, that aren't inline
		
		global $ft_dom;

		$code = array('');
		
		$body = $ft_dom->getElementsByTagName('body');
		
		//assuming there is one body tag. IMPROVE.
		//$elements = $body->item(0)->getElementsByTagName('link');

		foreach( $body as $body_test ) 
		{
			//1)
			$elements = $body_test->getElementsByTagName('link'); 
	        foreach ($elements as $element) { 
				if ($element->hasAttribute('href')) {
					if(strripos($element->getAttribute('href'),'.css') == (strlen($element->getAttribute('href')) - 4)) {
						$code[0] .=  Helper::printCodeWithLineNumber($element);
						return $code;
					}
				}
			}
			
			//2)
			$style_tags = $body_test->getElementsByTagName('style'); 
	        if($style_tags->length != 0) {
				$code[0] .=  Helper::printCodeWithLineNumber($style_tags->item(0));
				return $code;		
			}			
		}		
			 
		//$code = Helper::testForElement('style');		
		
		
		return false;
	}
	public function HasNestedTables()
	{
		global $ft_dom;
		$elements = $ft_dom->getElementsByTagName('table');
        foreach ($elements as $element) { 
			$nested_tables = $element->getElementsByTagName('table');				
	        foreach ($nested_tables as $nested) { 
				return true;
	        }
		}
		return false;			
	}

	public function HasDeeplyNestedTables()
	{
		return false;			
	}

	public function FaviconMissing()
	{
		global $ft_web_root;
			
		if(Helper::getHttpResponseCode($ft_web_root . 'favicon.ico') == 404 || Helper::getResourceSizeBytes($ft_web_root . 'favicon.ico') == 0) {
			return true;				
		}			
		return false;			
	}
					
    public function CssBeforeScript()
    {
		return false;
    }

    public function ManyInlineStylesFlag()
    {
		global $poorly_designed_catchall;
		$poorly_designed_catchall = 0;		
		global $ft_dom;
		$too_many_threshold = 8;
		$result_array = array();
		
		$elements = $ft_dom->getElementsByTagName('html');
		
		foreach($elements as $element) {
			Helper::recursivelySearchAttribute($element,'style');
		}

		if($poorly_designed_catchall > $too_many_threshold) {
			$result_array[0] = $poorly_designed_catchall;
			$result_array[1] = $too_many_threshold;
			return $result_array;
		}
				
		return false;
    }
	
    public function TitleElementMissing()
    {
		global $ft_dom;
		$elements = $ft_dom->getElementsByTagName('title');
        foreach ($elements as $element) { 
	        return false;
		}
		return true;
    }

    public function NoDoctype()
    {
        return false;
    }

    public function DoctypeNotFirstElement()
    {
		global $ft_data;
		
		//it's possible, and legal, to add a comment before the doctype. 
		//there are issues with it, in old versions of IE -- the page will be rendered in quirks mode. 
		//however, that would be a subtest of this one.
		
		//use a smaller string for efficiency, and for now, remove comments for the test. 
		$sub_str = substr($ft_data, 0, 2000);		
		$data_without_comments = Helper::removeCommentsFromString($sub_str);
		
		//if(strpos(trim(strtolower(substr($data_without_comments, 0, (strpos($data_without_comments,'>') + 1)))),'<!doctype') === false) {			
		if(strpos(trim(strtolower($data_without_comments)), '<!doctype') != 0) {
			return true;
		}
        return false;
    }

    public function BrokenLink()
    {
		global $ft_dom;
		
		$elements = $ft_dom->getElementsByTagName('a');
		$code = array('');
        foreach ($elements as $element) { 
			//check link status
			if ($element->hasAttribute('href')) {
				//link to check:
				$link = $element->getAttribute('href');
				
				//if matches url, skip TODO
				
				//also if using javascript: protocol, skip.
				if(strpos($element->getAttribute('href'),'javascript:') !== false || strpos($element->getAttribute('href'),'mailto:') !== false){
					continue;
				}
									
				$link = Helper::getAbsoluteResourceLink($link);
				if(Helper::getHttpResponseCode($link) == 404) {
				    /* Handle 404 here. */
				    $code[0] .=  Helper::printCodeWithLineNumber($element);
					return $code;
				}										
			}		        
		}
        return false;
    }

    public function BrokenImage()
    {
		global $ft_dom;
		
		$elements = $ft_dom->getElementsByTagName('img');
		$code = array('');
        foreach ($elements as $element) { 
			//check link status
			if ($element->hasAttribute('src')) {
				//link to check:
				$link = $element->getAttribute('src');
									
				$link = Helper::getAbsoluteResourceLink($link);

				if(Helper::getHttpResponseCode($link) == 404) {
				    /* Handle 404 here. */
				    $code[0] .=  Helper::printCodeWithLineNumber($element);
					return $code;
				} 							
			}		        
		}
        return false;
    }
    public function GifsUsed()
    {
		//to fix: if gifs are broken, size returns 0. if all gifs are broken, don't return restults_array
		global $ft_dom;
		$results_array = array();
		$imgs = $ft_dom->getElementsByTagName('img');
		$gifs = 0;
		$min_gifs_trigger = 3;
		$total_files_size = 0;
        foreach ($imgs as $img) { 
			if ($img->hasAttribute('src')) {
				if(strripos($img->getAttribute('src'),'.gif') == (strlen($img->getAttribute('src')) - 4)) {
					$gifs++;
			
					$link = Helper::getAbsoluteResourceLink($img->getAttribute('src'));
					$total_files_size += floatval(Helper::getResourceSizeBytes($link)); 
				}
			}
		}

		if($gifs >= $min_gifs_trigger) {
			$results_array[0] = $gifs;
			//THERE IS AN ERROR HERE!!!!
			//$results_array[1] = round(13.7548828125, 2, PHP_ROUND_HALF_UP) + 'KB'; //outputs: 13.75 and should be 13.76				
			$results_array[1] = round($total_files_size/1024,2, PHP_ROUND_HALF_UP) . 'KB';
			return $results_array;
		}						
        return false;
    }

    public function ManyImagesFlag()
    {
		global $ft_dom;
		$imgs = $ft_dom->getElementsByTagName('img');
		if($imgs->length > 10) return array($imgs->length);
        return false;
    }


    public function AmpersandUnescapedInLink()
    {
		$links_array = Helper::getAllDomLinks();
	    $code = array('');
		foreach($links_array as $link)
		{	
			if(strpos($link,'&') !== false)
			{
				//the next four chars must be amp; (five chars total)
				if(substr_compare($link,'&amp;',strpos($link,'&'),5) !== false)
				{
					$code[0] .= '`'.$link.'`';
				}
			}
			
		}
 		if($code[0] != '') {
			return $code;
		}
       return false;
	}
	
	//assume they only have one for now, and get the first one.
    public function DeprecatedElement()
    {		
		global $ft_dom;

	   $return_array = array();
	   $code = array('');
	   $deprecated_elements = array('applet',
									'dir',
									'isindex',
									'menu',
									'basefont',
									'center',
									'font',
									's',
									'strike',
									'u'
									);
									
		$deprecated_replacements = array();
		$deprecated_replacements['applet'] = 'object';
		$deprecated_replacements['dir'] = 'ul';
		$deprecated_replacements['isindex'] = 'input';
		$deprecated_replacements['menu'] = 'nav';
		$deprecated_replacements['basefont'] = 'css';
		$deprecated_replacements['center'] = 'css';
		$deprecated_replacements['font'] = 'css';
		$deprecated_replacements['s'] = 'css';
		$deprecated_replacements['strike'] = 'css';
		$deprecated_replacements['u'] = 'css';
					
		foreach($deprecated_elements as $deprecated_element) {
			$code = Helper::testForElement($deprecated_element);		
			if($code[0] != '') {
				$return_array[0] = $deprecated_element;
				if($deprecated_replacements[$deprecated_element] =='css') {
					$return_array[1] = 'CSS';
				}
				else 
				{
					$return_array[1] = '"'.$deprecated_replacements[$deprecated_element].'"';
				}
				
				//don't print a bunch of garbage. only if it's a short line, display code. otherwise, show snippet with line no
				if(strlen($code[0]) > 80) 
				{					
					$return_array[2] = substr($code[0], 0, 80) . '...`';
				} 
				else 
				{
					$return_array[2] = $code[0];
				}
				return $return_array;				
			}								
		}
		
        return false;
    }

    public function RequiredTagAttributePairMissing()
    {		
		return false;
	}
		
    public function ImgAltAttributeMissing()
    {
		global $ft_dom;
		$code = array('');
		
		$elements = $ft_dom->getElementsByTagName('img');


        foreach ($elements as $element) { 
			if (!$element->hasAttribute('alt') || $element->getAttribute('alt') == '') {
				$code[0] .=  Helper::printCodeWithLineNumber($element);					
			}	
		}		
		if($code[0] != '') {
			return $code;
		}		
		
        return false;
    }

    public function MissingImgHeightOrWidth()
    {
		global $ft_dom;
		$code = array('');		
		$elements = $ft_dom->getElementsByTagName('img');
        foreach ($elements as $element) { 
			if (!$element->hasAttribute('width') || !$element->hasAttribute('width')) {
				$code[0] .=  Helper::printCodeWithLineNumber($element);
			}	
		}

		if($code[0] != '') {
			return $code;
		}
        return false;
    }

    public function JavascriptInHref()
    {
		global $ft_dom;
		$code = array('');
		$elements = $ft_dom->getElementsByTagName('a');
        foreach ($elements as $element) { 
			if ($element->hasAttribute('href') && (strpos($element->getAttribute('href'),'javascript:') !== false)) {
				$code[0] .=  Helper::printCodeWithLineNumber($element);
			}	
		}			

		if($code[0] != '') {
			return $code;
		}
				
        return false;
    }

    public function BoldTags()
    {	
		global $ft_dom;

		$code = Helper::testForElement('b');		
		if(!empty($code)) {
			return $code;
		}		
	    return false;
    }

    public function MetadataContentEmpty()
	{
		global $ft_dom;
		global $ft_data;
		$code = array('');
		
		$elements = $ft_dom->getElementsByTagName('meta');

		
        foreach ($elements as $element) { 
			if ($element->hasAttribute('content') && $element->getAttribute('content') == '') {					
				$code[0] .=  Helper::printCodeWithLineNumber($element);					
			}	
		}		
		if($code[0] != '') {
			return $code;
		}	
		
        return false;
	}
	
	
}