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
	
	    public function ClassNameSameAsHtml5Element()
	    {	
			global $ft_dom;
			$html5_elements = " doctype a abbr address area article aside audio b base bb bdo blockquote body br button canvas caption cite code col colgroup command datagrid datalist dd del details dfn div dl dt em embed eventsource fieldset figcaption figure footer form h1 h2 h3 h4 h5 h6 head header hgroup hr html i iframe img input ins kbd keygen label legend li link mark map menu meta meter nav noscript object ol optgroup option output p param pre progress q ruby rp rt samp script section select small source span strong style sub summary sup table tbody td textarea tfoot th thead time title tr ul var video wbr ";
	        //loop through classes and if any appear in this string (with spaces), return true
			
			$doctype = $ft_dom->firstChild;
			$root_html = $ft_dom->getElementsByTagName('html');
			
			echo '<br><br>';
			var_dump($root_html);
			
			echo htmlspecialchars($ft_dom->saveXML($root_html->item(0)));

			//$xml = $ft_dom->saveXML($root_html->item(0));
			
			//foreach($html as $html_node) {
			//	echo '<br><br>';
			//	echo $html_node->nodeType;
				//$this->traverse($html);	
			//}
			
			return false;
	    }
	
	
		public function traverse( DomNode $node, $level=0 ) {
		  handle_node( $node, $level );
		 if ( $node->hasChildNodes() ) {
		   $children = $node->childNodes;
		   foreach( $children as $kid ) {
		     //if ( $kid->nodeType == XML_ELEMENT_NODE ) {
		       traverse( $kid, $level+1 );
		     //}
		   }
		 }
		}

		public function handle_node( DomNode $node, $level ) {
		  for ( $x=0; $x<$level; $x++ ) {
		    print " ";
		  }
		  //if ( $node->nodeType == XML_ELEMENT_NODE ) {
		    print $node->tagName."<br />\n";
		  //}
		}
		
		
}