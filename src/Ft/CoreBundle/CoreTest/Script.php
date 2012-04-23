<?php

namespace Ft\CoreBundle\CoreTest;

class Script
{
	    public function ScriptsInHead()
	    {
			$code = array('');
			$num_scripts = Helper::countElements('script','src');
			if($num_scripts > 1)
			{
				$code[0] = $num_scripts;
				return $code;
			}
	        return false;
	    }
}