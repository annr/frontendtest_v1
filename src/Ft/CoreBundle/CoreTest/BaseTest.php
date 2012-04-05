<?php

namespace Ft\CoreBundle\CoreTest;

abstract class BaseTest
{	
	public function __construct(\DomDocument $dom_document)
	{
			$this->dom_document = $dom_document;
			$this->runTest();
 	}

    public function runTest()
	{
		    
	}
	

    public function printSomething()
	{
	   echo 'something';
	}
	

    /**
     * @var boolean $result
     *
     * xxx
     */
    private $result;

    /**
     * @var DomDocument $dom_document
     *
     * xxx
     */
    private $dom_document;

    /**
     * @var string $heading
     *
     * The heading displayed in the report
     */
    private $heading;

    /**
     * @var text $description
     *
     * The default description displayed in the report
     */
    private $description;

    /**
     * @var text $extended_description
     *
     * An alternate, generally longer description displayed in the report. Both descriptions are never displayed.
     */
    private $extended_description;

    /**
     * @var text $more_details
     *
     * Some text to add to the description when helpful. This can be added to description or extended description, but done not replace either.
     */
    private $more_details;

    /**
     * @var text $resources
     *
     * links to resources for improving the front-end related to test
     */
    private $resources;

    /**
     * @var smallint $weight
     *
     * The weight of the test. Default 50.
     */
    private $weight = 50;

    /**
     * @var string $notes
     *
     * Admin notes about the test for debugging
     */
    private $notes;

    /**
     * @var boolean $run_by_default
     *
     * Will set default
     */
    private $run_by_default;

    /**
     * @var boolean $enabled
     *
     * Will set default
     */
    private $enabled;

    /**
     * @var boolean $print_line_numbers
     *
     * Will set default
     */
    private $print_line_numbers;

    /**
     * @var boolean $print_details
     *
     * Will set default
     */
    private $print_details;

    /**
     * @var string $version
     *
     * Default 1.0. I guess I need to manually set the version when the class is changed.
     */
    private $version;

    /**
     * Set heading
     *
     * @param string $heading
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
    }

    /**
     * Get heading
     *
     * @return string 
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set extended_description
     *
     * @param text $extendedDescription
     */
    public function setExtendedDescription($extendedDescription)
    {
        $this->extended_description = $extendedDescription;
    }

    /**
     * Get extended_description
     *
     * @return text 
     */
    public function getExtendedDescription()
    {
        return $this->extended_description;
    }

    /**
     * Set more_details
     *
     * @param text $moreDetails
     */
    public function setMoreDetails($moreDetails)
    {
        $this->more_details = $moreDetails;
    }

    /**
     * Get more_details
     *
     * @return text 
     */
    public function getMoreDetails()
    {
        return $this->more_details;
    }

    /**
     * Set resources
     *
     * @param text $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * Get resources
     *
     * @return text 
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set weight
     *
     * @param smallint $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return smallint 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set notes
     *
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set run_by_default
     *
     * @param boolean $runByDefault
     */
    public function setRunByDefault($runByDefault)
    {
        $this->run_by_default = $runByDefault;
    }

    /**
     * Get run_by_default
     *
     * @return boolean 
     */
    public function getRunByDefault()
    {
        return $this->run_by_default;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set print_line_numbers
     *
     * @param boolean $printLineNumbers
     */
    public function setPrintLineNumbers($printLineNumbers)
    {
        $this->print_line_numbers = $printLineNumbers;
    }

    /**
     * Get print_line_numbers
     *
     * @return boolean 
     */
    public function getPrintLineNumbers()
    {
        return $this->print_line_numbers;
    }

    /**
     * Set print_details
     *
     * @param boolean $printDetails
     */
    public function setPrintDetails($printDetails)
    {
        $this->print_details = $printDetails;
    }

    /**
     * Get print_details
     *
     * @return boolean 
     */
    public function getPrintDetails()
    {
        return $this->print_details;
    }

    /**
     * Set dom_document
     *
     * @param DomDocument $dom_document
     */
    public function setDomDocument($dom_document)
    {
        $this->dom_document = $dom_document;
    }

    /**
     * Get dom_document
     *
     * @return DomDocument 
     */
    public function getDomDocument()
    {
        return $this->dom_document;
    }

    /**
     * Set version
     *
     * @var string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return boolean
     */
    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

	public function toString()
    {
		$resultStr = 'No';
		if ($this->result) {
			$resultStr = 'Yes';				
		}
        return $this->class_name . ' ..... ' . $resultStr;
    }
}