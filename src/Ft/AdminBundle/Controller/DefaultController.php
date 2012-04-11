<?php

namespace Ft\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ft\CoreBundle\CoreTest\Test;
//use Ft\CoreBundle\CoreTest\HTML5\Html5Doctype;
use Ft\CoreBundle\CoreTest\TestSuite;

class DefaultController extends Controller
{
    public function testAction()
    {

	   $testSuite = new TestSuite();
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
		
		// 1. query for tests (will use config file in future)			
		$test1 = new Test('Is doctype HTML5?');
		$test1->setResult(true);

		$test2 = new Test('Is JavaScript deferred?');
		$test2->setResult(true);

		$test3 = new Test('Does each image have an alt tag?');
		$test3->setResult(true);

		$test4 = new Test('Does each image have width and height specified?');
		$test4->setResult(true);
		
		$test5 = new Test('Are resources minified?');
		$test5->setResult(true);
		
		$htmlUrlSet = isset($_POST['htmlUrl']);
		
		if ($htmlUrlSet) {
		 //get link with code from form action value:
		
		 $ch = \curl_init();	
		 \curl_setopt($ch, CURLOPT_URL,$_POST['htmlUrl']);
		 \curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		 \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		 \curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		 $data = \curl_exec($ch);
		 \curl_close($ch);
				
		 $dom = new \DomDocument();

		 @$dom->loadHTML($data);		
		
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
		
		}
		
        //return $this->render('FtAdminBundle:Default:index.html.twig');
        return $this->render('FtAdminBundle:Default:index.html.twig', array('htmlUrlSet' => $htmlUrlSet, 'test1' => $test1, 'test2' => $test2, 'test3' => $test3, 'test4' => $test4, 'test5' => $test5));

    }

}
