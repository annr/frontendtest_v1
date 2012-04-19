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
			
			// DoctypeNotFirstElement takes priority. if that is positive, we do not trigger the suggestion to upgrade to HTML5
			if(strpos(trim(strtolower(substr($ft_data, 0, (strpos($ft_data,'>') + 1)))),'<!doctype') === false) {
				return false;
			}
			if($ft_dom->doctype != null && $ft_dom->doctype->publicId != '') {			
				return true;
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

	        return false;
	    }
		
		public function getHtml5ClassElement( $node, $html5_elements) {			
		   if ($node->hasAttribute('class') && stripos($html5_elements,' '.$node->getAttribute('class').' ') !== false) 
		   { 
				$this->class_val_test_return_val = true; 
				return $node;
		   }
		   if ( $node->hasChildNodes() ) {
		     $children = $node->childNodes;
		     foreach( $children as $kid ) {
		       if ( $kid->nodeType == XML_ELEMENT_NODE ) {
		         $this->getHtml5ClassElement( $kid,$html5_elements );
		       }
		     }
		   }
		}

		public function print_node( $node ) {
		  if ( $node->nodeType == XML_ELEMENT_NODE ) {
		    print $node->tagName."<br />\n";
		  }
		}
		
		
}