<?php

namespace Ft\CoreBundle\CoreTest\Script;

use Ft\CoreBundle\CoreTest\BaseTest;

class IsJavaScriptInHead extends BaseTest
{
	// required settings
	private $heading = 'Scripts linked in head block content display'; 
	private $description = 'It\'s a habit link JavaScript in the HTML document head, but usually unnecessary. Browsers treat scripts differently than other resources, limiting simultaneous downloads. This causing a bottleneck. Move scripts to the bottom of the page.'; 
	
	// optional settings	
	//private $version = '1.0';
	private $weight = 70;
	//private $enabled = true;
	// .....
	//private $description_extended = 'Attribute X is deprecated'; 

    public function __construct(\DomDocument $dom)
    {
		parent::__construct($dom);
		$this->setHeading('Scripts linked in head block content display');
		$this->setDescription('It\'s a habit link JavaScript in the HTML document head, but usually unnecessary. Browsers treat scripts differently than other resources, limiting simultaneous downloads. This causing a bottleneck. Move scripts to the bottom of the page.');
		$this->setWeight(70);		
    }

    public function runTest()
	{	
		$elements = $this->getDomDocument()->getElementsByTagName('script');		
        foreach ($elements as $element) { 
		    if ($element->parentNode->nodeName == 'head') {
				$this->setResult(true);			
			};
        }

	}

}