<?php

namespace Ft\CoreBundle\CoreTest;

use Ft\CoreBundle\CoreTest\Helper;

class HTML
{

	public $poorly_designed_recursive_search_output = array('');

	public function DuplicateId() {
		global $poorly_designed_catchall;	   
		global $poorly_designed_catchall_element_array;
		$code = array('');
		$poorly_designed_catchall = array();		
		$poorly_designed_catchall_element_array = array();	
			
		global $ft_dom;		
		$elements = $ft_dom->getElementsByTagName('html');
		
		foreach($elements as $element) {
			Helper::recursivelyGetDuplicateAttributeValue($element,'id');
		}
				
		if(!empty($poorly_designed_catchall_element_array)) { 
			foreach($poorly_designed_catchall_element_array as $element) {
				$code[0] .= $element;
			}
			$code[1] = '';
			if(count($poorly_designed_catchall_element_array) > 1) { $code[1] = 's'; }
			return $code;
		}
				
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
		global $ft_dom;
		$elements = $ft_dom->getElementsByTagName('table');
        foreach ($elements as $element) { 
			$nested_tables = $element->getElementsByTagName('table');				
	        foreach ($nested_tables as $nested) { 
				$deeply_nested_tables = $element->getElementsByTagName('table');				
		        foreach ($deeply_nested_tables as $deeply_nested) { 
					return true;
		        }
	        }
		}
		return false;			
	}

	//we just check to see if they have a favicon set in the HTML. If the file is missing, it will show up in brokenlink.
	public function FaviconMissing()
	{
		global $ft_dom;
	    //global $ft_data;

		//$pattern = '/.*<link.*href=.*favicon\.ico.*>/';		
		//preg_match($pattern,$ft_data,$match);
		
		//if(!isset($match[0])) { return true; }
		
		//unfortunately is does not need to be called favicon. re-do test.
		$head = $ft_dom->getElementsByTagName('head');		
		$elements = $head->item(0)->getElementsByTagName('link');
		
        foreach ($elements as $element) { 
			if($element->hasAttribute('rel') && strpos($element->getAttribute('rel'),'icon') !== false) { return false; }		
		}
				
		return true;			
	}
					
    public function ScriptBeforeCss()
    {
		global $ft_dom;
		global $ft_run_log;

		$elements = $ft_dom->getElementsByTagName('head');
		
		$code = array('');
		$prev_script = null;
		$print_once = false;
		$code[1] = 0; //number of css after scripts.
		$code[2] = 0; //number of scripts before css.
    	if ( $elements->item(0)->hasChildNodes() ) {
		    $children = $elements->item(0)->childNodes;
		    foreach( $children as $kid ) {
		    	if ( $kid->nodeType == XML_ELEMENT_NODE ) {
			        if($kid->tagName == 'script' && $kid->hasAttribute('src')) {
						$prev_script = $kid;
						$print_once = false;	
					} elseif ($kid->tagName == 'link') {
						if($kid->hasAttribute('href') && (strpos(strtolower($kid->getAttribute('href')),'.css') !== false)) {
							if(isset($prev_script)) {
								if(!$print_once) {
									$code[1]++;
									$code[2]++;
									$code[0] .= Helper::printCodeWithLineNumber($prev_script);
									$code[0] .= "\nappears before\n\n";
									$code[0] .= Helper::printCodeWithLineNumber($kid);
								} else {
									$code[1]++;
									$code[0] .= "\nand\n\n";
									$code[0] .= Helper::printCodeWithLineNumber($kid);
								}								
								$print_once = true;
							}
						}
					}
					if($print_once) { $code[0] .= "\n"; }	
					
			    }
		    }
	    }
	
		$ft_run_log .= ',"Number stylesheets after scripts":'. $code[1] . ',"Number scripts before stylesheets":'. $code[2];

		if($code[1] > 1) { $code[1] = 's'; } else { $code[1] = ''; }
		if($code[2] > 1) { $code[2] = 's'; } else { $code[2] = ''; }
				
		//if code is too long, empty and return. 
 		if($code[0] != '') {
			if(strlen($code[0]) > 300) { $code[0] = ''; }
			return $code;
		}	

		return false;
    }

    public function ManyInlineStylesFlag()
    {
		global $poorly_designed_catchall;
		$poorly_designed_catchall = 0;		
		global $ft_dom;
		$too_many_threshold = 20;
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
		if(!Helper::DoctypeFirstElementCheck()) {
			return true;
		}
        return false;
    }


    public function BrokenLinkDiagnostics()
    {
		global $ft_dom;
		$code = array('');
		$code[1] = 0;
		$code[2] = '';
		$passed_elem_array = array();
		$url_array = array();

		$elements = $ft_dom->getElementsByTagName('link');
        foreach ($elements as $element) { 	
			if($element->hasAttribute('href')) { 				
				if(!in_array($element->getAttribute('href'),$url_array))
				{ 				
					$url = Helper::getAbsoluteResourceLink($element->getAttribute('href'));	
					$link_time_start = Helper::microtime_float();
							
					$headers = get_headers($url, 1);			
					$header_str = explode(' ',$headers[0]);				
					if($header_str[1] == '404') {
						$code[1]++;
						$code[0] .=  Helper::printCodeWithLineNumber($element);
					} 
				
					$link_time_end = Helper::microtime_float();
					$link_time = $link_time_end - $link_time_start;
					
					$url_array[] = round($link_time,4) . ' :: ' . $element->getAttribute('href') ;						
				} 
		 	}
		 }

		$elements = $ft_dom->getElementsByTagName('a');
		//if there are > 100 links, let's not do this check for now, or make a trigger to run it manually.
		if($elements->length > 100) { return false; }
        foreach ($elements as $element) { 	
			if($element->hasAttribute('href')) { 				
				if(!in_array($element->getAttribute('href'),$url_array))
				{ 				
					$url = Helper::getAbsoluteResourceLink($element->getAttribute('href'));	
					$link_time_start = Helper::microtime_float();

					//error_log($url);	
						
					$headers = get_headers($url, 1);			
					$header_str = explode(' ',$headers[0]);				
					if($header_str[1] == '404') {
						$code[1]++;
						$code[0] .=  Helper::printCodeWithLineNumber($element);
					} 
				
					$link_time_end = Helper::microtime_float();
					$link_time = $link_time_end - $link_time_start;
					
					$url_array[] = round($link_time,4) . ' :: ' . $element->getAttribute('href') ;						
				} 
		 	}
		 }
		
		//var_dump($url_array);
		return false;

	}
	
    public function BrokenLink()
    {
		global $ft_dom;
		$code = array('');
		$code[1] = 0;
		$code[2] = '';
		$passed_elem_array = array();
		$url_array = array();

		$elements = $ft_dom->getElementsByTagName('a');

		//only make this test if there is a reasonable number of links.
		if($elements->length < 50) { 
	        foreach ($elements as $element) { 	
				if($element->hasAttribute('href')) { 
					if(strpos($element->getAttribute('href'),'javascript:') !== false || strpos($element->getAttribute('href'),'mailto:') !== false){
						error_log('returning from mailto or js:');
						continue;
					}				    		
					if(!in_array($element->getAttribute('href'),$url_array))
					{ 				
						$url = Helper::getAbsoluteResourceLink($element->getAttribute('href'));	
						//error_log('checking...' . $url);			
						$headers = get_headers($url, 1);			
						$header_str = explode(' ',$headers[0]);				
						if($header_str[1] == '404') {
							$code[1]++;
							$code[0] .=  Helper::printCodeWithLineNumber($element);
						} 
						$url_array[] = $element->getAttribute('href');						
					} 
			 	}
			 }
		} 
		
		$elements = $ft_dom->getElementsByTagName('link');
        foreach ($elements as $element) { 	
			if($element->hasAttribute('href')) { 				
				if(!in_array($element->getAttribute('href'),$url_array))
				{ 				
					$url = Helper::getAbsoluteResourceLink($element->getAttribute('href'));	
					//error_log('checking...' . $url);			
					$headers = get_headers($url, 1);			
					$header_str = explode(' ',$headers[0]);				
					if($header_str[1] == '404') {
						$code[1]++;
						$code[0] .=  Helper::printCodeWithLineNumber($element);
					} 
					$url_array[] = $element->getAttribute('href');						
				} 
		 	}
		 }
		
		 if($code[0] != '') {
			if($code[1] > 1) { $code[2] = 's'; }
			return $code;
		 }
		
        return false;
    }

    public function BrokenImage()
    {		
		global $ft_dom;
		$code = array('');
		$code[1] = 0;
		$code[2] = '';
		$passed_elem_array = array();
		$elements = $ft_dom->getElementsByTagName('img');
		$url_array = array();
        foreach ($elements as $element) { 	
			if($element->hasAttribute('src')) { 				
				if(!in_array($element->getAttribute('src'),$url_array))
				{ 				
					$url = Helper::getAbsoluteResourceLink($element->getAttribute('src'));	
					$headers = get_headers($url, 1);			
					$header_str = explode(' ',$headers[0]);				
					if($header_str[1] == '404') {
						$code[1]++;
						$code[0] .=  Helper::printCodeWithLineNumber($element);
					} 
					$url_array[] = $element->getAttribute('src');
				}					
		 	}
		 }
		
		 if($code[0] != '') {
			if($code[1] > 1) { $code[2] = 's'; }
			return $code;
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
		$min_gifs_trigger = 7;
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


    public function DoubleSlashesInUrl()
    {
		//besides paired with the protocol, are there redundant slashes?
		//in what browser does this not work?
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
		$code[1] = 0;
		$code[2] = '';
		foreach($links_array as $link)
		{	
			if(strpos($link,'&') !== false)
			{
				//the next four chars must be amp; (five chars total)
				if(substr_compare($link,'&amp;',strpos($link,'&'),5) !== false)
				{
					if($code[1] <= Helper::$max_disp_threshold) { 
						$link = Helper::addWhitespaceForReportFormatting($link);
						$code[0] .= '`'.$link.'`';
					}
					$code[1]++;
				}
			}
			
		}
 		if($code[0] != '') {
			if($code[1] > 1) { $code[2] = 's'; }
			if($code[1] > Helper::$max_disp_threshold) { $code[0] .= '...'; }
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

	/* 	this cannot be run -- it is far too slow.
    public function DeprecatedAttributeNotAlign()
    {		
		global $ft_dom;
		$code = array('');

		$deprecated_attributes['img'] = array('vspace','border','hspace');
		$deprecated_attributes['object'] = array('vspace','border','hspace');
		$deprecated_attributes['body'] = array('alink','background','bgcolor','link','text','vlink');
		$deprecated_attributes['table'] = array('bgcolor');
		$deprecated_attributes['td'] = array('bgcolor','nowrap','width');
		$deprecated_attributes['tr'] = array('bgcolor');
		$deprecated_attributes['th'] = array('bgcolor','nowrap','width');
		$deprecated_attributes['hr'] = array('noshade','size','width');
		$deprecated_attributes['ol'] = array('start');
		$deprecated_attributes['li'] = array('type','value');
		$deprecated_attributes['pre'] = array('width');
		
		foreach($deprecated_attributes as $key => $values)
		{
			$elements = $ft_dom->getElementsByTagName($key);
	        foreach ($elements as $element) { 
				foreach($values as $value)
				{
					if ($element->hasAttribute($value)) {
						echo "<br>found deprecated attribute: " . $key . ":" . $value;
						$code[0] = $value;
						$code[1] = $key;
						$code[2] = Helper::printCodeWithLineNumber($element);					
 						return $code;					
					}
				}
			}		
			
		}

		return false;
	}
	*/

    //this is the not perfect but optimized version. 
    public function DeprecatedAttributeAlt()
    {
		global $ft_data;
		$code = array('');
	
		$deprecated_attributes = array('nowrap',
										'align',
										'start',
										'vspace',
										'border',
										'hspace',
										'alink',
										'background',
										'bgcolor',
										'link',
										'text',
										'vlink',
										'noshade',
										//'size',
										//'type',
										//'value',
										'width');
	
/*									
		$deprecated_in_tags['img'] = array('vspace','border','hspace','width');
		$deprecated_in_tags['object'] = array('vspace','border','hspace');
		$deprecated_in_tags['body'] = array('alink','background','bgcolor','link','text','vlink');
		$deprecated_in_tags['table'] = array('bgcolor');
		$deprecated_in_tags['td'] = array('bgcolor','nowrap','width');
		$deprecated_in_tags['tr'] = array('bgcolor');
		$deprecated_in_tags['th'] = array('bgcolor','nowrap','width');
		$deprecated_in_tags['hr'] = array('noshade','size','width');
		$deprecated_in_tags['ol'] = array('start');
		$deprecated_in_tags['li'] = array('type','value');
		$deprecated_in_tags['pre'] = array('width');
		$deprecated_in_tags['div'] = array('width');
*/							
		//foreach($deprecated_in_tags as $key => $attribute_array) 
		//{

			foreach($deprecated_attributes as $attribute) 
			{
				//echo "\ntesting " . $attribute . " for $key.\n\n";

				//for efficiency, does the attribute even exist?
				
				//get the array of positions for this!!!
				$strpos = strpos($ft_data,' '.$attribute.'=');
				if($strpos !== false)
				{
					//this is far from a perfect search:
					//find the "<" backwards from strpos
					//echo "start reverse search: " . ($strpos - strlen($ft_data));
					$open_tag_start = strrpos($ft_data,'<',$strpos - strlen($ft_data));
					$tag = substr($ft_data,$open_tag_start+1,(strpos($ft_data,' ',$open_tag_start) - ($open_tag_start+1)));					
					if($attribute == 'width' && $tag == 'img') { continue; }		
					
					$code[1] = $tag;
					$code[0] = 	$attribute;										
					$code[2] = '`'.substr($ft_data,$open_tag_start,((strpos($ft_data,'>',$open_tag_start)+1) - $open_tag_start)).'`';				
					return $code;						

					//$open_tag = substr($ft_data, $open_tag_start, strpos($ft_data,' ',$open_tag_start));
					
					//and then only log if open_tag is $key
					
					//$pattern = '/<'.$key.'\s+.*'.$attribute.'=.*>/';	
							
					//preg_match($pattern,$ft_data,$match1);
					//the strpos search is not very good. if it wasn't really found, don't return it. 
					//the is an issue with content seeming like code to FET.
					//echo print_r($match1);
					
					/*
					if(isset($match1[0])) {
						$code[0] = $deprecated_attribute;
						//we have an issue with results with nested tags. 
						//this is returned:
						//<div class="logomain"> <a href="/"><img src="http://www.ekwity.com/images//logos/ekwity_whitelogo.png" alt="Ekwity.com" width="316" height="120"/></a> </div>
						//therefore let's test the tag from where deprecated element is found.
												
						$code[1] = substr($match1[0],1,strpos($match1[0],' ')-1);						
						if($deprecated_attribute == 'width' && strtolower($code[1]) == 'img') { continue; }		
						$code[2] = '`'.$match1[0].'`';				
						return $code;
					} else {
						//the previous search was simply not very good.
						continue;
					}
					*/				
				}
			
			//}
		}	
		return false;				
	}


    //this is the not perfect but optimized version. 
    public function DeprecatedAttribute()
    {
		global $ft_dom;
		$code = array('');
		$code[1] = '';	
		$code[2] = 0;	
		$instance_array = array();						
		$deprecated_in_tags['img'] = array('vspace','border','hspace','align');
		$deprecated_in_tags['object'] = array('vspace','border','hspace');
		$deprecated_in_tags['body'] = array('alink','background','bgcolor','link','text','vlink');
		$deprecated_in_tags['table'] = array('bgcolor','align');
		$deprecated_in_tags['td'] = array('bgcolor','nowrap','width');
		$deprecated_in_tags['tr'] = array('bgcolor');
		$deprecated_in_tags['th'] = array('bgcolor','nowrap','width');
		$deprecated_in_tags['hr'] = array('noshade','size','width');
		$deprecated_in_tags['ol'] = array('start');
		$deprecated_in_tags['li'] = array('type','value');
		$deprecated_in_tags['pre'] = array('width');
		$deprecated_in_tags['div'] = array('align');
		$deprecated_in_tags['p'] = array('align');
		$deprecated_in_tags['h1'] = array('align');
		$deprecated_in_tags['h2'] = array('align');
		$deprecated_in_tags['h3'] = array('align');
		$deprecated_in_tags['h4'] = array('align');
		$deprecated_in_tags['h5'] = array('align');
		$deprecated_in_tags['h6'] = array('align');
		$deprecated_in_tags['hr'] = array('align','noshade','size','width');
						
		foreach($deprecated_in_tags as $key => $attribute_array) 
		{
			$elements = $ft_dom->getElementsByTagName($key);
			foreach($elements as $element) 
			{
				foreach($attribute_array as $attribute) 
				{
					if($element->hasAttribute($attribute)) {
						$code[2]++;
						//don't show too many of these lines. 
						if($code[2] < 4 && !in_array("$key-$attribute",$instance_array)) { 
							$code[0] .= '"'.$attribute.'" is a deprecated HTML4 attribute for `<'.$key.">`\n\n";
						} elseif ($code[2] == 4) {
							$code[0] .= '...';							
						}	
						$instance_array[] = "$key-$attribute";				
					}
				}
			}			
		}	
		
		if($code[0] != '') {
			if($code[2] > 1) { $code[1] = 's'; }
			return $code;
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
		$code[1] = 0; 
		$code[2] = '';
		$elements = $ft_dom->getElementsByTagName('img');
		
        foreach ($elements as $element) { 	
			//exit if img points to another server (for now). it is likely a pixel.
			
			//if ($element->hasAttribute('width')) { echo "\nwidth: " . $element->getAttribute('width'); }
			//if ($element->hasAttribute('height')) { echo "\nheight: " . $element->getAttribute('height'); }
			if($element->hasAttribute('src') && (strpos($element->getAttribute('src'),'pixel') !== false || strpos($element->getAttribute('src'),'doubleclick') !== false || strpos($element->getAttribute('src'),'shareasale') !== false )) { continue; }
						
			if ((!$element->hasAttribute('width') && !Helper::hasInlineStyleAttribute($element,'width')) || (!$element->hasAttribute('height') && !Helper::hasInlineStyleAttribute($element,'width'))) {
				if($code[1] <= Helper::$max_disp_threshold) { $code[0] .= Helper::printCodeWithLineNumber($element); }
				$code[1]++;
			}	
		}

		if($code[0] != '') {
			if($code[1] > 1) { $code[2] = 's'; }
			if($code[1] > Helper::$max_disp_threshold) { $code[0] .= '...'; }
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
		
    public function HasDocumentWrite()
    {	
		if(Helper::stringFound('document.write(')) {
			return true;
		}		
	    return false;
    }

	
	
}