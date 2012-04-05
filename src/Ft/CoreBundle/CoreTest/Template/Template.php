<?php

namespace Ft\CoreBundle\CoreTest\XXFolder;

use Ft\CoreBundle\CoreTest\BaseTest;

class XXClass extends BaseTest
{
	
    public function __construct(\DomDocument $dom)
    {
		parent::__construct($dom);
		
		// required settings
		$this->setHeading('XX should be XX');
		$this->setDescription('Attribute X is deprecated');

		// optional settings
		//$this->setWeight(70);
		//$this->setVersion('1.0');
    }

    public function runTest()
	{	
		// heart of the test. it must set boolean $result.
	}

}