<?php

namespace Ft\CoreBundle\CoreTest;

class HTML
{

	    public function DuplicateElementsWhichShouldBeUnique()
	    {
			global $ft_dom;
			$unique_elements = array('head','title','body');
			$elements = $ft_dom->getElementsByTagName('head');
	        foreach ($elements as $element) { 
		        return false;
			}
			return true;
	    }


	    public function CssBeforeScript()
	    {
			return false;
	    }

	    public function TitleElementMissing()
	    {
			global $ft_dom;
			$elements = $ft_dom->getElementsByTagName('title');
	        foreach ($elements as $element) { 
		        return false;
			}
			return true;
	    }

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
			global $ft_dom;
			global $ft_url;
			global $ft_url_root;
			global $ft_web_root;
			
			$elements = $ft_dom->getElementsByTagName('a');
	        foreach ($elements as $element) { 
				//check link status
				if ($element->hasAttribute('href')) {
					//link to check:
					$link = $element->getAttribute('href');
					//if matches url, skip
					if($link == $ft_url || $link == ($ft_url . '/')) { 
						echo '<br><br>it matches, skip: ' .$link;
						continue;					
					} 
					//if http, skip build path.	
					elseif (substr($link, 0, 7) === 'http://' || substr($link, 0, 8) === 'https://') { 											
						//link is fine, do not modify
					}
					//if forward slash, add url minus forward slash.
					elseif (substr($link, 0, 1) === '/') { 
						$link = $ft_web_root.substr($link, 1); 
					}
					//if relative, add url making sure forward slash exists
					elseif (substr($link, 0, 1) !== '/') { 
						$link = $ft_url_root.$link; 
					}
					
					$handle = curl_init($link);
					curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

					/* Get the HTML or whatever is linked in $url. */
					$response = curl_exec($handle);

					/* Check for 404 (file not found). */
					$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

					curl_close($handle);
					if($httpCode == 404) {
					    /* Handle 404 here. */
						return true;
					}
										
				}		        
			}
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
			}
	        return false;
	    }

	    public function MissingImgHeightOrWidth()
	    {
			global $ft_dom;
			
			$imgs = $ft_dom->getElementsByTagName('img');
	        foreach ($imgs as $img) { 
				if (!$img->hasAttribute('width') || !$img->hasAttribute('width')) {
					return true;
				}	
			}
	        return false;
	    }


	
	    public function JavascriptInHref()
	    {
			global $ft_dom;
			$elements = $ft_dom->getElementsByTagName('a');
	        foreach ($elements as $element) { 
				if ($element->hasAttribute('href') && (strpos($element->getAttribute('href'),'javascript:') !== false)) {
					return true;
				}	
			}			
			
	        return false;
	    }
	
	    public function BoldTags()
	    {	
			global $ft_dom;
			$b = $ft_dom->getElementsByTagName('b');
			if($b->length > 0) { return true;}
		    return false;
	    }
	
	    public function MetadataContentEmpty()
		{
			global $ft_dom;
			global $ft_data;
			$code = array('');
			
			$elements = $ft_dom->getElementsByTagName('meta');
						
	        foreach ($elements as $element) { 
				if ($element->hasAttribute('content') && $element->getAttribute('content') == '') {
					$doc=new \DOMDocument();
					$doc->appendChild($doc->importNode($element,true));
					$meta = $doc->saveHTML();
					
					//get line breaks previous to $meta
					$text = substr($ft_data, 0, stripos($ft_data, $meta));
					$line = 1; //the first line is one.
					$line += substr_count($text, "\n");
					$code[0] .=  '`('. $line . ') '. trim($meta) . '`' . "  \n\r";					
				}	
			}		
			if(!empty($code)) {
				return $code;
			}	
			
	        return false;
		}


	
	
}