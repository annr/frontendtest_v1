<?php

namespace Ft\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Ft\CoreBundle\CoreTest\HTML5\Html5Doctype;
use Ft\CoreBundle\CoreTest\HTML5;
use Ft\CoreBundle\CoreTest\HTML;
use Ft\CoreBundle\CoreTest\Script;
use Ft\CoreBundle\Entity\TestResult;

class DefaultController extends Controller
{	
	//index takes an ft_request id, queries for URL, and runs the suite of tests.
    public function indexAction($id)
    {
		 //made the URL, the raw data and the dom document global
		 global $ft_url;
		 global $ft_data;
		 global $ft_dom;

         $em = $this->getDoctrine()->getEntityManager();
         $ft_request = $em->getRepository('FtHomeBundle:FtRequest')->findOneById($id);

		 //$ft_url = $ft_request->getUrl();
		 $ft_url = 'http://localhost/tests/test-h.html';
		
		 //get link with code from form action value:

		 $ch = \curl_init();	
		 \curl_setopt($ch, CURLOPT_URL,$ft_url);
		 \curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		 \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		 \curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		 $ft_data = \curl_exec($ch);
		 \curl_close($ch);
				
		 $ft_dom = new \DomDocument();

		 @$ft_dom->loadHTML($ft_data);
		
		 //$ft_dom->validate();
						
		//if($ft_dom->doctype != null) {
			//echo '<br>publicId: ' . $ft_dom->doctype->publicId;
			//echo '<br>systemId: ' . $dom->doctype->systemId;
			//echo '<br>name: ' . $dom->doctype->name;
		//}
			
		//query the core test table to determine which tests to run?

        $entities = $em->getRepository('FtCoreBundle:CoreTest')->findAll();

		//run whichever tests it can find.
		//depending on outcome, result will be saved for request, with custom data injected
		$HTML5 = new HTML5();
		$HTML = new HTML();
		$Script = new Script();

		
        foreach($entities as $entity) {
	
			//var_dump($entity);
			//do we have the class?
			$packageName = $entity->getPackageName();			
			$className = $entity->getClassName();
			
			//try {
				$result_instance = ${$packageName}->$className();
			//} catch (Exception $e) {	
			//	echo 'could not run test '.$entity->getPackageName().':'.$entity->getClassName();
			//	continue;
			//}
			
			//if result instance is not false, then add to result_array.
			if($result_instance) {
				//merge what is returned with the coretest record. add to result array.
				if(is_bool($result_instance)) {
					
					//persist test result
					echo '<br>'.$entity->getClassName().' true. insert into table.';
					//if a record already exists for that test name (class) continue to next coretest
					$ex_result = $em->getRepository('FtCoreBundle:TestResult')->findOneBy(array('ft_request_id' => $id, 'class_name' => $entity->getClassName()));
					if($ex_result) { continue; }				
/*			
			        $result = new TestResult();
			        $result->setHeading($entity->getHeading());					
			        $result->setWeight($entity->getWeight());					
			        $result->setBody($entity->getDescription());					
			        $result->setClassName($entity->getClassName());	
			        $result->setKind($entity->getPackageName());	
			        $result->setFtRequest($ft_request);	
			        $em->persist($result);	
				
*/					
				}
			} else {
				echo '<br>'.$entity->getClassName().' false.';
			}
		}	
		
		//$em->flush();

/*		echo '<br><br>done with tests. looping thru results.<br><br>';
		
		foreach($result_array as $result) {
			var_dump($result);
			echo '<br><br>';
		}
        	

	   $testSuite = new TestSuite();			
	   $url = 'http://localhost/tests/test-h.html';	
	   $data = $testSuite->getDocument($url);
	   $testSuite->runDocumentTests($data);	
	   $testSuite->runDomTests($testSuite->getDomDocument($data));	
	   //echo $testSuite->getDetailsHtml($testSuite->getResultArray());
*/	
	
        return $this->render('FtCoreBundle:Default:index.html.twig');
    }
}
