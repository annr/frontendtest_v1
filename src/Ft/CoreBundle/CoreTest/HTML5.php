<?php

namespace Ft\CoreBundle\CoreTest;

class HTML5
{
		public $class_val_test_return_val;
		
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
		
	    public function ClassNameSameAsHtml5Element()
	    {	
			//VERY POOR CODE!!!
			//THE WAY THIS IS WRITTEN, IT WILL ONLY FIND ONE EXAMPLE. SHOULD BE IMPROVED.
			global $ft_dom;
			$this->class_val_test_return_val = false;

			$html5_elements = " doctype a abbr address area article aside audio b base bb bdo blockquote body br button canvas caption cite code col colgroup command datagrid datalist dd del details dfn div dl dt em embed eventsource fieldset figcaption figure footer form h1 h2 h3 h4 h5 h6 head header hgroup hr html i iframe img input ins kbd keygen label legend li link mark map menu meta meter nav noscript object ol optgroup option output p param pre progress q ruby rp rt samp script section select small source span strong style sub summary sup table tbody td textarea tfoot th thead time title tr ul var video wbr ";
	        //loop through classes and if any appear in this string (with spaces), return true
			
			$doctype = $ft_dom->firstChild;
			$body_html = $ft_dom->getElementsByTagName('body');
						
			foreach($body_html as $html_node) {
				$this->getHtml5ClassElement($html_node,$html5_elements);
			}
			
			return $this->class_val_test_return_val;
	    }
		
		public function getHtml5ClassElement( $node, $html5_elements) {			
		   if ($node->hasAttribute('class') && stripos($html5_elements,' '.$node->getAttribute('class').' ') !== false) { $this->class_val_test_return_val = true; }
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