<?php

// THE STEPS FOR ADDING A TEST ARE NUMBERED BELOW

namespace Ft\CoreBundle\CoreTest;

use Ft\CoreBundle\CoreTest\HTML5\IsNotHtml5Doctype;
use Ft\CoreBundle\CoreTest\Script\IsJavaScriptInHead;
use Ft\CoreBundle\CoreTest\HTML\DoImagesHaveAltAttributes;

// 1. INCLUDE CLASS ABOVE

abstract class TestManager
{
    public function getDomDocument($url)
    {
		$ch = \curl_init();	
		\curl_setopt($ch, CURLOPT_URL,$url);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		\curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		\curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		$data = \curl_exec($ch);
		\curl_close($ch);
				
		$dom = new \DomDocument();

		@$dom->loadHTML($data);
		
		return $dom;
	}
	
    public function runDomTests($dom)
    {
		$resultArray = array();
						
        $resultArray[] = new IsNotHtml5Doctype($dom);
        $resultArray[] = new IsJavaScriptInHead($dom);
        $resultArray[] = new DoImagesHaveAltAttributes($dom);
	
		// 2. DECLARE CLASS AND CALL RETURNRESULT() ABOVE
		
		// build raw text and html: 
		$details_raw = '';
		$details_html = '';
		
		foreach ($resultArray as $result) {
			if($result->getResult()) {
				$details_raw .= $result->getHeading();	
				$details_raw .= '\n\n';	
				$details_raw .= $result->getDescription();	
				$details_raw .= '\n\n';	

				$details_html .= '<h2>'.$result->getHeading().'</h2>';	
				$details_html .= '<p>'.$result->getDescription().'</p>';
			}				
		}
		
		echo $details_html;
    }

/*
	public function getDetailsHtml() {
		$str = '';
		foreach ($resultArray as $result) {
			$str .= '<h2>'.$result->getHeading().'</h2>';	
			$str .= '<p>'.$result->getDescription().'</p>';				
		}
		return $str;	
	
	}

	public function getDetailsRaw() {
		$str = '';
		foreach ($resultArray as $result) {
			$str .= $result->getHeading();	
			$str .= '\n\n';	
			$str .= $result->getDescription();	
			$str .= '\n\n';	
		}
		return $str;		
	}
*/

}