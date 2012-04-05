<?php

namespace Ft\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\CoreBundle\Entity\CoreTest
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CoreTest
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $class_name
     *
     * @ORM\Column(name="class_name", type="string", length=255)
     */
    private $class_name;

    /**
     * @var string $package_name
     *
     * @ORM\Column(name="package_name", type="string", length=255, nullable=true)
     */
    private $package_name;

    /**
     * @var string $heading
     *
     * @ORM\Column(name="heading", type="string", length=2000)
     */
    private $heading;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var text $extended_description
     *
     * @ORM\Column(name="extended_description", type="text", nullable=true)
     */
    private $extended_description;

    /**
     * @var text $more_details
     *
     * @ORM\Column(name="more_details", type="text", nullable=true)
     */
    private $more_details;

    /**
     * @var text $resources
     *
     * @ORM\Column(name="resources", type="text", nullable=true)
     */
    private $resources;

    /**
     * @var smallint $weight
     *
     * @ORM\Column(name="weight", type="smallint", nullable=true)
     */
    private $weight;

    /**
     * @var string $notes
     *
     * @ORM\Column(name="notes", type="string", length=2000, nullable=true)
     */
    private $notes;

    /**
     * @var boolean $run_by_default
     *
     * @ORM\Column(name="run_by_default", type="boolean", nullable=true)
     */
    private $run_by_default;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @var boolean $print_line_numbers
     *
     * @ORM\Column(name="print_line_numbers", type="boolean", nullable=true)
     */
    private $print_line_numbers;

    /**
     * @var boolean $print_details
     *
     * @ORM\Column(name="print_details", type="boolean", nullable=true)
     */
    private $print_details;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set class_name
     *
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->class_name = $className;
    }

    /**
     * Get class_name
     *
     * @return string 
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * Set package_name
     *
     * @param string $packageName
     */
    public function setPackageName($packageName)
    {
        $this->package_name = $packageName;
    }

    /**
     * Get package_name
     *
     * @return string 
     */
    public function getPackageName()
    {
        return $this->package_name;
    }

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
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}