<?php

namespace Ft\CoreBundle\CoreTest;

class Content
{
    public function PossibleStaleCopyrightEndYear()
    {
		global $ft_data;
		//if the text appears within a parent that has a name like footer or bottom or something, and the year is past, trigger this.

	    //Copyright © 2005-2012 
	
		//an improved version would be to allow space around the hyphen.
		//also, could be an em dash or n dash.
		//$pattern = '/Copyright\s*(&copy;|©)\s*([1-2][0-9][0-9][0-9])\s?[^-]/';		
		$pattern = '/Copyright\s*(&copy;|©)\s*([1-2][0-9][0-9][0-9])[^-]/';		
		preg_match($pattern,$ft_data,$match);
		//there are quite a few ways to include a copyright. 
		//just try to get 70%, or 130% since it will not be sent automatically.
		
		if(isset($match[2]) && intval($match[2]) < date('Y')) {
			$code[0] = '`'.$match[0].'`';										
			return $code;
		}			
        return false;
    }
	
	public function DuplicateResourceIncluded()
	{
		//make an array of script src and link href, and see if you get any duplicates. trim query strings from these. 
		global $ft_dom;
		return false;
		
	}
}