<?php

namespace Ft\CoreBundle\CoreTest;

use Ft\CoreBundle\CoreTest\Helper;

class CSS
{

    public function xBangImportant()
    {
		return false;
	}


	    public function CssInHeadTotalFilesizeLarge()
	    {
			global $ft_dom;
			global $ft_run_log;

			$head = $ft_dom->getElementsByTagName('head');			
			$code = array('');
			$elements = $head->item(0)->getElementsByTagName('link');
			$code[1] = '';
			$code[2] = 0;
	        foreach ($elements as $element) { 
				if ($element->hasAttribute('href') && strpos(strtolower($element->getAttribute('href')),'.css') !==false) {
					$link = Helper::getAbsoluteResourceLink($element->getAttribute('href'));	
					$code[2] += floatval(Helper::getResourceSizeBytes($link))/1024;					
					//echo "<br>t". $code[2];					
					$code[0]++;	
				}	
			}			
			$ft_run_log .= ',"css total file size in head":"' .$code[2]. 'KB"';
			if($code[2] > 99.9)
			{		
				$code[2] = round($code[2],2);
				if($code[0] > 1) { $code[1] = 's'; }
				return $code;
			}			
	        return false;
	    }
	
		public function CssOutsideOfHead()
		{
			//there are two ways this can be true. 
			//1) the link tag is used with an external css file outside of head (a rare mistake which may work in some browsers) and
			//2) style tags are found in the body, that aren't inline

			//for now, we'll just

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
						if(strripos($element->getAttribute('href'),'.css') == (strlen($element->getAttribute('href')) - 4) && strpos($element->getAttribute('href'),'jquery') == false) {
							$code[0] .=  Helper::printCodeWithLineNumber($element);
							return $code;
						}
					}
				}

				//2)
				/*
				$style_tags = $body_test->getElementsByTagName('style'); 
		        if($style_tags->length != 0) {
					//only kick off this result if there is quite a bit of css inline
					$code[0] .=  Helper::printCodeWithLineNumber($style_tags->item(0));
					return $code;		
				}	
				*/		
			}		

			//$code = Helper::testForElement('style');		

			return false;
		}
	
}