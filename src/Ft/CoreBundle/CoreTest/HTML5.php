<?php

namespace Ft\CoreBundle\CoreTest;

class HTML5
{
		public $class_val_test_return_val;
		
	    public function SemanticallyPoorSiteFlag()
	    {
	        return false;
	    }

	    public function NotHtml5Doctype()
	    {
			global $ft_dom;
			global $ft_data;
			$code = array('');
			if(!Helper::DoctypeFirstElementCheck()) {
				return false;
			}
						
			//now what kind of document is it? put document type in %1%
			//if it's an HTML5 document, return false
			$start_str_assumption = '-//W3C//DTD ';			
			if($ft_dom->doctype != null && $ft_dom->doctype->publicId != '') {		
				$code[0] = substr($ft_dom->doctype->publicId,strlen($start_str_assumption),strrpos($ft_dom->doctype->publicId,'//') - strlen($start_str_assumption));
			}
			if($code[0] != '') {
				return $code;
			}		
	        return false;
	    }

	    public function Html5ElementsWOShim()
	    {
			$new_html5_elements = "article aside bdi command details summary figure figcaption footer header hgroup mark meter nav progress ruby rt rp section time wbr audio video source embed track canvas datalist keygen output";	
	        return false;
	    }
	
	    public function HasHtml5ElementWODoctype() 
	    {
	        return false;		
	    }

	    public function InvalidElementInHead()
	    {
			//$allowed_elements = array('title','base','link','style','meta','script','noscript','command');
	        return false;
	    }
		
	    public function DivClassNameOrIdSimilarToHtml5Element()
	    {	
		
			global $poorly_designed_catchall;
			global $poorly_designed_catchall_element_array;
			
			$poorly_designed_catchall = 0;
			$poorly_designed_catchall_element_array = array();

			//VERY POOR CODE!!!
			//THE WAY THIS IS WRITTEN, IT WILL ONLY FIND ONE EXAMPLE. SHOULD BE IMPROVED.
			global $ft_dom;
			$code = array('');
			$code[1] = 0; 
			
			//$html5_elements = "a abbr address area article aside audio b base bb bdo blockquote br button canvas caption cite code col colgroup command datagrid datalist dd del details dfn div dl dt em embed eventsource fieldset figcaption figure footer form h1 h2 h3 h4 h5 h6 header hgroup hr html i iframe img input ins kbd keygen label legend li link mark map menu meta meter nav noscript object ol optgroup option output p param pre progress q ruby rp rt samp script section select small source span strong style sub summary sup tbody td textarea tfoot th thead time tr ul var video wbr ";
			$html5_elements_suspicious = "navigation navbar headerbar footerbar menubar title heading abbr address article aside blockquote button canvas caption cite code col colgroup command datagrid datalist details em fieldset figcaption figure footer header hgroup hr label legend li menu meter nav noscript object ol optgroup output p param pre progress section small source span strong sub summary sup tbody tfoot thead time ul var video ";
	        //loop through classes and if any appear in this string (with spaces), return true
		
			$html5_elements_array = explode(' ',$html5_elements_suspicious);
			
			$doctype = $ft_dom->firstChild;
			$elements = $ft_dom->getElementsByTagName('div');
			
			//only search divs for now. 			
			//outer foreach will only loop once.			
			foreach($elements as $element) {
				if(($element->hasAttribute('class') && in_array($element->getAttribute('class'),$html5_elements_array)) || ($element->hasAttribute('id') && in_array($element->getAttribute('id'),$html5_elements_array))) {
					$code[1]++;					
					$code[0] .=  Helper::printCodeWithLineNumber($element);	
				}
			}
										
			if($code[0] != '') {
				if($code[1] > 1) { $code[1] = 's'; } else  { $code[1] = ''; }
				return $code;
			}				

	        return false;
	    }
		
		
	    public function IsHtml5HasXhtmlCloseTags()
	    {
			global $ft_data;			
		
			if(Helper::HasHtml5Doctype()) {				
				$test = substr_count($ft_data,' />');			
				if($test > 0) { return array($test); }
			}
			return false;
		}		
		
		
}