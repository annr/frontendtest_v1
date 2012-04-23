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
		
	    public function ClassNameSameAsHtml5Element()
	    {	
		/*
			global $poorly_designed_catchall;
			global $poorly_designed_catchall_element_array;
			
			$poorly_designed_catchall = 0;
			$poorly_designed_catchall_element_array = array();

			//VERY POOR CODE!!!
			//THE WAY THIS IS WRITTEN, IT WILL ONLY FIND ONE EXAMPLE. SHOULD BE IMPROVED.
			global $ft_dom;
			$code = array('');

			$html5_elements = "a abbr address area article aside audio b base bb bdo blockquote br button canvas caption cite code col colgroup command datagrid datalist dd del details dfn div dl dt em embed eventsource fieldset figcaption figure footer form h1 h2 h3 h4 h5 h6 header hgroup hr html i iframe img input ins kbd keygen label legend li link mark map menu meta meter nav noscript object ol optgroup option output p param pre progress q ruby rp rt samp script section select small source span strong style sub summary sup tbody td textarea tfoot th thead time tr ul var video wbr ";
	        //loop through classes and if any appear in this string (with spaces), return true
			
			$html5_elements_array = explode(' ',$html5_elements);
			
			$doctype = $ft_dom->firstChild;
			$body_html = $ft_dom->getElementsByTagName('body');
			
			//outer foreach will only loop once.			
			foreach($body_html as $html_node) {
				foreach($html5_elements_array as $html5_element) {
					Helper::recursivelySearchAttributeValue($html_node,'class',$html5_element);
				}
			}
	        foreach ($poorly_designed_catchall_element_array as $element) { 
				$code[0] .=  Helper::printCodeWithLineNumber($element);	
			}
										
			if($code[0] != '') {
				return $code;
			}
			*/		

	        return false;
	    }
		
		
	    public function HasXhtmlCloseTags()
	    {
			return false;
		}		
		
		
}