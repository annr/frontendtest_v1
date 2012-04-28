<?php

namespace Ft\CoreBundle\CoreTest;

class Filedata
{

	public function UnusuallyLargeFile() {		
		return false;
	}
	
	public function IncorrectImagePixelSize()
	{
		global $ft_dom;
		$code = array('');	
		
		$elements = $ft_dom->getElementsByTagName('img');
		$badimg = array();
		$code[1] = 0;
	    foreach ($elements as $element) { 
		
			if($element->hasAttribute('src')) {
				if ($element->hasAttribute('width') || $element->hasAttribute('height')) {
					$link = Helper::getAbsoluteResourceLink($element->getAttribute('src'));
					if(Helper::getHttpResponseCode($link) != 404) { 
						
						list($width, $height) = getimagesize($link);
						if ($element->hasAttribute('width')) {
							if($element->getAttribute('width') != $width) {
								if($code[1] <= Helper::$max_disp_threshold) { $code[0] .= Helper::printCodeWithLineNumber($element) . "\nshould be width=\"$width\""; }
								$badimg[] = $element;
								$code[1]++;
							}
						}
						if ($element->hasAttribute('height')) {
							if($element->getAttribute('height') != $height) {
								if($code[1] <= Helper::$max_disp_threshold) {
									if(in_array($element, $badimg)) {
										$code[0] .= "\n and height=\"$height\"";
									} else {
										$code[0] .= Helper::printCodeWithLineNumber($element) . "\nshould be height=\"$height\"";
										$code[1]++;									
									}
								}
							}
						}														
					}
					if(in_array($element, $badimg)) { $code[0] .= "\n\n"; }						
				}
			}
		}

		if($code[0] != '') {
			if($code[1] > Helper::$max_disp_threshold) { $code[0] .= '...'; }
			if($code[1] > 1) { $code[2] = 's'; }			
			return $code;
		}
	    return false;
	}
	
	public function LargeScriptNotMinified()
    {	

		//this only get the first large script and exits. All of them could be collected,
		//the savings in filesize can be shared..etc. there's some work to-do.
		global $ft_dom;
		$code = array('');
		$elements = $ft_dom->getElementsByTagName('script');
		$code[1] = 0;
		$code[2] = '';
		
		//INCLUDES OPTIMIZELY HACK BECAUSE THE LINKED SCRIPT IS NOT MINIFIED
        foreach ($elements as $element) { 
			if ($element->hasAttribute('src') && strpos($element->getAttribute('src'),'.min.') === false && strpos($element->getAttribute('src'),'optimizely.com') === false) {
				//if ".min." is in the name, don't bother :)				
				$link = Helper::getAbsoluteResourceLink($element->getAttribute('src'));	
				
				$bytes_size = floatval(Helper::getResourceSizeBytes($link));

				if($bytes_size > 5000)
				{	
					//see if the script is not minified.
					if(!Helper::isMinified($link))
					{
						$code[1]++;
						//$code[0] .= '`'.Helper::printCodeWithLineNumber($element,false) . ' ' . round($bytes_size/1024,1, PHP_ROUND_HALF_UP) .'K`';
						$code[0] .= Helper::printCodeWithLineNumber($element);
						//return $code;
					} 
				}	
			}	
		}
		if($code[0] != '') {
			if($code[1] > 1) { $code[2] = 's'; }
			return $code;
		}			
	    return false;
    }

	public function MultipleLocalStyleSheets()
    {
	/*
		global $ft_dom;
		$code = array('');
		$elements = $ft_dom->getElementsByTagName('link');
		$code[1] = 0;
		$code[2] = '';
		
        foreach ($elements as $element) { 
			if ($element->hasAttribute('href') && strpos(strtolower($element->getAttribute('href')),'.css') !==false) {
				
			}
		}
		*/
		
		return false;
	}
	
	public function LargeStylesheetNotMinified()
    {	
		//this only gets the first large script, then exits. All of them could be collected,
		//the savings in total filesize can be shared..etc. there's some work to-do.
		global $ft_dom;
		$code = array('');
		$elements = $ft_dom->getElementsByTagName('link');
		$code[1] = 0;
		$code[2] = '';
        foreach ($elements as $element) { 
			if ($element->hasAttribute('href') && strpos(strtolower($element->getAttribute('href')),'.css') !==false && strpos($element->getAttribute('href'),'.min.') ===false) {
//			if ($element->hasAttribute('href') && strpos($element->getAttribute('href'),'.min.') ===false) {
				//if ".min." is in the name, don't bother :)				
				if(strpos($element->getAttribute('href'),'.min.') !==false) { continue; }
				$link = Helper::getAbsoluteResourceLink($element->getAttribute('href'));					
				$bytes_size = floatval(Helper::getResourceSizeBytes($link));
				if($bytes_size > 1000)
				{	
					//see if the script is not minified.
					if(!Helper::isMinified($link))
					{
						$code[1]++;
						//$code[0] .= '`'.Helper::printCodeWithLineNumber($element,false) . ' (' . round($bytes_size/1024,2, PHP_ROUND_HALF_UP) .'K)`';
						$code[0] .= Helper::printCodeWithLineNumber($element);
						//return $code;
					} 
				}	
			}	
		}
		if($code[0] != '') {
			if($code[1] > 1) { $code[2] = 's'; }
			return $code;
		}			
	    return false;
    }
	

}