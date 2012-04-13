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
		 global $ft_host;
		 global $ft_get;
		 global $ft_url_root;
		 global $ft_web_root;
		 global $ft_data;
		 global $ft_dom;
		 global $ft_dom_xml;
		 global $ft_http_request;

         $em = $this->getDoctrine()->getEntityManager();
         $ft_request = $em->getRepository('FtHomeBundle:FtRequest')->findOneById($id);

		 //$ft_url = $ft_request->getUrl();
		 $ft_url = 'http://localhost/tests/test-x.html';
		 //$ft_url = 'http://www.bhnc.com';

		 $ft_url_root = substr($ft_url, 0, strrpos($ft_url, '/'));
		 
		//$ft_url_root = host from header plus get with the file removed. 
		//therefore if get is
		
		 //if resources starts with / add host. if none, add host+get with file removed.
		
		 //get link with code from form action value:

		 $ch = \curl_init();	
		 \curl_setopt($ch, CURLOPT_URL,$ft_url);
		 \curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		 \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		 \curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		 \curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		 $ft_data = \curl_exec($ch);

		if(!\curl_errno($ch))
		{
		 	$ft_http_request = \curl_getinfo($ch);
			//foreach($ft_http_request as $key => $value){
			//  echo $key.":" . $value . "<br>\r\n";
			//}			
		} else {
			error_log('CURL ERROR WITH URL: '.$url);
		}
		
		$http_request_split = explode("\n", $ft_http_request['request_header']);
		$get_split = explode(" ", $http_request_split[0]);
		$host_split = explode(" ", $http_request_split[1]);
		
		$ft_host = trim($host_split[1]);
		$ft_get = $get_split[1];
		$protocol = explode('/',$get_split[2]);	
		$ft_url_root = strtolower($protocol[0]) . '://' . $ft_host . substr($ft_get, 0, (strrpos($ft_get, '/') + 1));
		
		$ft_web_root = strtolower($protocol[0]) . '://' . $ft_host . '/';
	
		\curl_close($ch);

		if(!($ft_http_request['http_code'] == '200')) {
			echo 'HTTP RESPONSE CODE OTHER THAN 200 FOR: '.$url . "\n\rexiting....";
			error_log('HTTP RESPONSE CODE OTHER THAN 200 FOR: '.$url);
			exit;			
		}

		//if(intval($ft_http_request['download_content_length']) < 1000 ) {
		//	echo "HTTP RESPONSE HAS VERY LITTLE CODE. NOT MUCH TO TEST? (".$url . ")\n\rexiting....";
		//	error_log('HTTP RESPONSE HAS VERY LITTLE CODE. NOT MUCH TO TEST? ('.$url . ')');
		//	exit;			
		//}

		if($ft_http_request['content_type'] != 'text/html' && $ft_http_request['content_type'] != 'application/xhtml+xml') {
			echo 'NOT A SUPPORTED CONTENT TYPE ('.$ft_http_request['content_type'].'): '.$url . "\n\rexiting....";
			error_log('NOT A SUPPORTED CONTENT TYPE ('.$ft_http_request['content_type'].'): '.$url);
			exit;			
		}
										
		$ft_dom = new \DomDocument();
		$ft_dom->preserveWhiteSpace = true;

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
			
			//THIS IS A POOR WAY TO CHECK IF THE TESTS EXIST. WHAT IF THE SAME TEST NAME EXISTS IN TWO "PACKAGES"?
			if(method_exists($HTML5,$className) || method_exists($HTML,$className) || method_exists($Script,$className)){ 
			//try {
				$result_instance = ${$packageName}->$className();
			//} catch (Exception $e) {	
			//	echo 'could not run test '.$entity->getPackageName().':'.$entity->getClassName();
			//	continue;
			//}
			}
		
			//if result instance is not false, then add to result_array.
			if($result_instance) {
				//merge what is returned with the coretest record. add to result array.
				//persist test result
				//echo '<br>'.$entity->getClassName().' true. insert into table.';
				//if a record already exists for that test name (class) continue to next coretest
				$ex_result = $em->getRepository('FtCoreBundle:TestResult')->findOneBy(array('ft_request_id' => $id, 'class_name' => $entity->getClassName()));
				if($ex_result) { continue; }
				/*			
		        $result = new TestResult();
		        $result->setWeight($entity->getWeight());					
		        $result->setClassName($entity->getClassName());	
		        $result->setKind($entity->getPackageName());	
		        $result->setFtRequest($ft_request);	
		        $em->persist($result);	
				*/								
				if(is_bool($result_instance)) {
					/*
			        $result->setBody($entity->getDescription());										
			        $result->setHeading($entity->getHeading());					
					*/				
				} elseif(is_array($result_instance)) {
					//in this case, we need to take every array value that is returned and substitute %X% in the template.
					$coretestDesc = $entity->getDescription();
					$coretestHead = $entity->getHeading();
					$index = 1;
					foreach($result_instance as $str_to_insert) {
						$str_to_substitue = '%'.$index.'%';
						$coretestDesc = str_replace($str_to_substitue, $str_to_insert, $coretestDesc);
						$coretestHead = str_replace($str_to_substitue, $str_to_insert, $coretestHead);
						$index++;
					}
					//$result->setBody($coretestDesc);
					//$result->setHeading($coretestHead);
					 
				}
			}  else {
				//echo '<br>'.$entity->getClassName().' false.';
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
