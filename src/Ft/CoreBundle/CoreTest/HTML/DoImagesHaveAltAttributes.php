<?php

namespace Ft\CoreBundle\CoreTest\HTML;

use Ft\CoreBundle\CoreTest\BaseTest;

class DoImagesHaveAltAttributes extends BaseTest
{

    public function __construct(\DomDocument $dom)
    {
		parent::__construct($dom);
		// required settings
		$this->setHeading('The ALT attribute is required for &lt;img&gt;');
		$this->setDescription('You have at least one image with no ALT attribute.');

		// optional settings
		//$this->setWeight(70);
		//$this->setVersion('1.0');
    }

    public function runTest()
	{	
		// heart of the test. it must set boolean $result.
		$imgs = $this->getDomDocument()->getElementsByTagName('img');
        foreach ($imgs as $img) { 
			if (!$img->hasAttribute('alt')) {
				$this->setResult(true);
			}			
		}
	}
    

}