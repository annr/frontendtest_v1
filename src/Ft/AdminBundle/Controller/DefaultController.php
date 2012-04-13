<?php

namespace Ft\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function testAction()
    {

	   	$url = 'http://localhost/tests/test-h.html';

		$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";

		 //save http request in 
		 //get link with code from form action value:
		
		 $ch = \curl_init();	
		 \curl_setopt($ch, CURLOPT_URL,$url);
		 \curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		 \curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		 \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		 \curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		 \curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		 $data = \curl_exec($ch);
				
		 \curl_close($ch);
   
         echo $data;
					

		
/*	
		//query the core test table to determine which tests to run?
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FtCoreBundle:CoreTest')->findById(1);

		//run whichever tests it can find.
		//depending on outcome, result will be saved for request, with custom data injected

        foreach($entities as $entity) {
			var_dump($entity);
			//do we have the class?
			new Html5Doctype();
		}		

	   $testSuite = new TestSuite();			
	   $url = 'http://localhost/tests/test-h.html';	
	   $data = $testSuite->getDocument($url);
	   $testSuite->runDocumentTests($data);	
	   $testSuite->runDomTests($testSuite->getDomDocument($data));	
	   //echo $testSuite->getDetailsHtml($testSuite->getResultArray());
*/	
       return $this->render('FtAdminBundle:Default:test.html.twig');
	}


    public function indexAction()
    {		
		
		$url = 'http://localhost/tests/test-h.html';
				
		 //get link with code from form action value:
		
		 $ch = \curl_init();	
		 \curl_setopt($ch, CURLOPT_URL,$url);
		 \curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		 \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		 \curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		 $data = \curl_exec($ch);
		 \curl_close($ch);
				
		 $dom = new \DomDocument();

		 @$dom->loadHTML($data);
		
				
/*		
		// TEST1	
		if($dom->doctype != null) {
			//echo '<br>publicId: ' . $dom->doctype->publicId;
			//echo '<br>systemId: ' . $dom->doctype->systemId;
			//echo '<br>name: ' . $dom->doctype->name;
			if($dom->doctype->publicId != '') {
				$test1->setResult(false);
			}
		} 

		// TEST2	
		$elements = $dom->getElementsByTagName('script');		
        foreach ($elements as $element) { 
		    if ($element->parentNode->nodeName == 'head') {
				$test2->setResult(false);			
			};
        }

		// TEST3	
		$imgs = $dom->getElementsByTagName('img');
        foreach ($imgs as $img) { 
			echo 'testing img...' + $img->hasAttribute('alt');
			if (!$img->hasAttribute('alt')) {
				$test3->setResult(false);
			}	
				
			//if (!$first_image->hasAttribute('width')) {
			//	$output .= 'width should to be set for images<br>';			
			//}							
			//if (!$first_image->hasAttribute('height')) {
			//	$output .= 'height should to be set for images';			
			//}
		
		}

		//what is the DOCTYPE?
		
		//if HTML5 doctype, run thru HTML5 validator, save output.
*/		

		
        //return $this->render('FtAdminBundle:Default:index.html.twig');
        return $this->render('FtAdminBundle:Default:index.html.twig', array('htmlUrlSet' => $htmlUrlSet, 'test1' => $test1, 'test2' => $test2, 'test3' => $test3, 'test4' => $test4, 'test5' => $test5));

    }

}
