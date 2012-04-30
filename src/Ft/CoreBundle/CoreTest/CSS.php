<?php

namespace Ft\CoreBundle\CoreTest;

class CSS
{

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
	
}