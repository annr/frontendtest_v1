<?php

namespace Ft\CoreBundle\CoreTest;

use Ft\CoreBundle\CoreTest\Helper;

class HTML
{

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
			$helper = new Helper();			
			if($helper->getHttpResponseCode($ft_web_root . 'favicon.ico') == 404 || $helper->getResourceSizeBytes($ft_web_root . 'favicon.ico') == 0) {
				return true;				
			}			
			return false;			
		}
						
	    public function CssBeforeScript()
	    {
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
			
			if(strpos(trim(strtolower(substr($ft_data, 0, (strpos($ft_data,'>') + 1)))),'<!doctype') === false) {
				return true;
			}
	        return false;
	    }
	
	    public function BrokenLink()
	    {
			global $ft_dom;
			
			$helper = new Helper();
			$elements = $ft_dom->getElementsByTagName('a');
			$code = array('');
	        foreach ($elements as $element) { 
				//check link status
				if ($element->hasAttribute('href')) {
					//link to check:
					$link = $element->getAttribute('href');
					
					//if matches url, skip TODO
					
					//also if using javascript: protocol, skip.
					if(strpos($element->getAttribute('href'),'javascript:') !== false){
						continue;
					}
										
					$link = $helper->getAbsoluteResourceLink($link);

					if($helper->getHttpResponseCode($link) == 404) {
					    /* Handle 404 here. */
					    $code[0] .=  $helper->printCodeWithLineNumber($element);
						return $code;
					}										
				}		        
			}
	        return false;
	    }
	
	    public function GifsUsed()
	    {
			global $ft_dom;
			$results_array = array();
			$imgs = $ft_dom->getElementsByTagName('img');
			$gifs = 0;
			$total_files_size = 0;
	        foreach ($imgs as $img) { 
				if ($img->hasAttribute('src')) {
					if(strripos($img->getAttribute('src'),'.gif') == (strlen($img->getAttribute('src')) - 4)) {
						$gifs++;
						$helper = new Helper();
						$link = $helper->getAbsoluteResourceLink($img->getAttribute('src'));
						$total_files_size += intval($helper->getResourceSizeBytes($link)); 
					}
				}
			}

			if($gifs) {
				$results_array[0] = $gifs;
				//THERE IS AN ERROR HERE!!!!
				//$results_array[1] = round(13.7548828125, 2, PHP_ROUND_HALF_UP) + 'KB'; //outputs: 13.75				
				$results_array[1] = round($total_files_size/1024,2, PHP_ROUND_HALF_UP) + 'KB';
				return true;
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

		//assume they only have one for now, and get the first one.
	    public function DeprecatedElement()
	    {		
			global $ft_dom;
			$helper = new Helper();
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
				$code = $helper->testForElement($deprecated_element);		
				if(!empty($code)) {
					$return_array[0] = $deprecated_element;
					$return_array[1] = $deprecated_replacements[$deprecated_element];
					$return_array[2] = $code[0];
					var_dump($return_array);	
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
			$helper = new Helper();

	        foreach ($elements as $element) { 
				if (!$element->hasAttribute('alt') || $element->getAttribute('alt') == '') {
					$code[0] .=  $helper->printCodeWithLineNumber($element);					
				}	
			}		
			if(!empty($code)) {
				return $code;
			}		
			
	        return false;
	    }

	    public function MissingImgHeightOrWidth()
	    {
			global $ft_dom;
			
			$elements = $ft_dom->getElementsByTagName('img');
	        foreach ($elements as $element) { 
				if (!$element->hasAttribute('width') || !$element->hasAttribute('width')) {
					return true;
				}	
			}
	        return false;
	    }
	
	    public function JavascriptInHref()
	    {
			global $ft_dom;
			$elements = $ft_dom->getElementsByTagName('a');
	        foreach ($elements as $element) { 
				if ($element->hasAttribute('href') && (strpos($element->getAttribute('href'),'javascript:') !== false)) {
					return true;
				}	
			}			
			
	        return false;
	    }
	
	    public function BoldTags()
	    {	
			global $ft_dom;
			$helper = new Helper();
			$code = $helper->testForElement('b');		
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
			$helper = new Helper();
			
	        foreach ($elements as $element) { 
				if ($element->hasAttribute('content') && $element->getAttribute('content') == '') {					
					$code[0] .=  $helper->printCodeWithLineNumber($element);					
				}	
			}		
			if(!empty($code)) {
				return $code;
			}	
			
	        return false;
		}


	
	
}