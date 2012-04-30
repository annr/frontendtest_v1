<?php

namespace Ft\CoreBundle\CoreTest;

class Script
{
		//until you understand the maleffects better, only return true if they have > 3 scripts in head,
		//and none of those scripts are optimizely, typekit and jquery...
	    public function MultipleScriptsInHead()
	    {
			global $ft_dom;
			$elements = $ft_dom->getElementsByTagName('head');			
			$code = array('');
			$num_scripts = Helper::getBlockingScriptsInHead($elements->item(0));
			if($num_scripts > 1)
			{
				$code[0] = $num_scripts;
				return $code;
			}
	        return false;
	    }

	    public function ScriptsInHeadTotalFilesizeLarge()
	    {
			global $ft_dom;
			$head = $ft_dom->getElementsByTagName('head');			
			$code = array('');
			$elements = $head->item(0)->getElementsByTagName('script');
			$code[1] = '';
			$code[2] = 0;
	        foreach ($elements as $element) { 
				if ($element->hasAttribute('src')) {
					$link = Helper::getAbsoluteResourceLink($element->getAttribute('src'));	
					$code[2] += floatval(Helper::getResourceSizeBytes($link))/1024;					
					//echo "<br>t". $code[2];					
					$code[0]++;	
				}	
			}
			
			if($code[2] > 180)
			{		
				$code[2] = round($code[2],2);
				if($code[0] > 1) { $code[1] = 's'; }
				return $code;
			}			
	        return false;
	    }

	
	public function LocalJQueryLink() {
		return false;
	}
	
	public function ScriptTagHasType() {
		global $ft_data;
		return array(substr_count($ft_data,'text/javascript'));
	}
}