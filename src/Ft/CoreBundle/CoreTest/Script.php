<?php

namespace Ft\CoreBundle\CoreTest;

class Script
{
		//until you understand the maleffects better, only return true if they have > 3 scripts in head,
		//and none of those scripts are optimizely, typekit and jquery...
	    public function ScriptsInHead()
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
	
	public function LocalJQueryLink() {
		return false;
	}
	
	public function ScriptTagHasType() {
		global $ft_data;
		return array(substr_count($ft_data,'text/javascript'));
	}
}