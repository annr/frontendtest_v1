<?php

namespace Ft\CoreBundle\CoreTest;

class HTML
{
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
	        return false;
	    }
	
	    public function GifsUsed()
	    {
			global $ft_dom;
			
	        return false;
	    }
	
	    public function ManyImages()
	    {
	        return false;
	    }
	
	    public function JavascriptInHref()
	    {
	        return false;
	    }
	
}