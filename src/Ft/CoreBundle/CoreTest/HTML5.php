<?php

namespace Ft\CoreBundle\CoreTest;

class HTML5
{
	    public function NotHtml5Doctype()
	    {
			global $ft_dom;
			if($ft_dom->doctype != null && $ft_dom->doctype->publicId != '') {			
				return true;
			}
	        return false;
	    }

	    public function Html5ElementsWOShim()
	    {
	        return false;
	    }
		
}