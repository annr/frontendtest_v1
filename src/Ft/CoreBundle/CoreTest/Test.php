<?php

namespace Ft\CoreBundle\CoreTest;

class Test
{
	    protected $name;
	
	    /**
	     * @param string $name The logging channel
	     */
	    public function __construct($name)
	    {
	        $this->name = $name;
	    }

	    /**
	     * @return string
	     */
	    public function getName()
	    {
	        return $this->name;
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
	        return $this->name . ' ..... ' . $resultStr;
	    }
}