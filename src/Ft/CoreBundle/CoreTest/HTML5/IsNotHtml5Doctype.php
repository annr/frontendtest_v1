<?php

namespace Ft\CoreBundle\CoreTest\HTML5;

use Ft\CoreBundle\CoreTest\BaseTest;

class IsNotHtml5Doctype extends BaseTest
{
    public function __construct(\DomDocument $dom)
    {
		parent::__construct($dom);
		//$this->setVersion(1.1);
		$this->setHeading('Site uses outdated doctype. Should be <!DOCTYPE html>.');
		$this->setDescription('The source at %site_url% should be updated to the HTML5 Doctype: <!DOCTYPE html>. The current doctype at line %line_number% is %current_doctype%. The doctype should precede all other source.');
		$this->setWeight(70);		
    }

    public function runTest()
	{	
		if($this->getDomDocument()->doctype != null) {
			//echo '<br>publicId: ' . $this->getDomDocument()->doctype->publicId;
			//echo '<br>systemId: ' . $this->getDomDocument()->doctype->systemId;
			//echo '<br>name: ' . $this->getDomDocument()->doctype->name;
			if($this->getDomDocument()->doctype->publicId != '') {
				
				$this->setResult(true);
			}
		}
	}

}