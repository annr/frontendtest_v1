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

			$imgs = $ft_dom->getElementsByTagName('img');
			$gifs = 0;
	        foreach ($imgs as $img) { 
				if ($img->hasAttribute('src')) {
					if(strripos($img->getAttribute('src'),'.gif') == (strlen($img->getAttribute('src')) - 4)) $gifs++;
				}
			}
			//echo 'total gifs...' . $gifs;			
			if($gifs) {
				return true;
			}						
	        return false;
	    }
	
	    public function ManyImages()
	    {
	        return false;
	    }


	    public function MissingAltAttribute()
	    {
			global $ft_dom;
			
			$imgs = $ft_dom->getElementsByTagName('img');
	        foreach ($imgs as $img) { 
				if (!$img->hasAttribute('alt')) {
					return true;
				}	

				//if (!$first_image->hasAttribute('width')) {
				//	$output .= 'width should to be set for images<br>';			
				//}							
				//if (!$first_image->hasAttribute('height')) {
				//	$output .= 'height should to be set for images';			
				//}

			}
	        return false;
	    }

	    public function MissingImgHeightOrWidth()
	    {
			global $ft_dom;
			
			$imgs = $ft_dom->getElementsByTagName('img');
	        foreach ($imgs as $img) { 
				if (!$img->hasAttribute('width') || !$img->hasAttribute('width')) {
					echo $img->getAttribute('src') . ' has no height or width';
					return true;
				}	
			}
	        return false;
	    }


	
	    public function JavascriptInHref()
	    {
			global $ft_dom;
			
			
	        return false;
	    }
	
	    public function BoldTags()
	    {	
			global $ft_dom;
			$b = $ft_dom->getElementsByTagName('b');
			if($b->length > 0) { return true; }			
	        return false;
	    }


	
	
}