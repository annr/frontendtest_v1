<?php

namespace Ft\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\CoreBundle\Entity\TestResult
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TestResult
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
     * @var bigint $ft_request_id
     *
     * @ORM\Column(name="ft_request_id", type="integer")
     */
    private $ft_request_id;

    /**
     * @var FtRequest $ft_request
     * @ORM\ManyToOne(targetEntity="Ft\HomeBundle\Entity\FtRequest", inversedBy="test_results")
     * @ORM\JoinColumn(name="ft_request_id", referencedColumnName="id")
    */
    protected $ft_request;

    /**
     * @var string $kind
     *
     * @ORM\Column(name="kind", type="string", length=255, nullable=true)
     */
    private $kind;

    /**
     * @var string $class_name
     *
     * @ORM\Column(name="class_name", type="string", length=255, nullable=true)
     */
    private $class_name;

    /**
     * @var string $version
     *
     * @ORM\Column(name="version", type="string", length=4, nullable=true)
     */
    private $version;

    /**
     * @var string $heading
     *
     * @ORM\Column(name="heading", type="string", length=3000)
     */
    private $heading;

    /**
     * @var text $body
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var smallint $weight
     *
     * @ORM\Column(name="weight", type="smallint", nullable=true)
     */
    private $weight;


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
     * Set ft_request_id
     *
     * @param bigint $ftRequestId
     */
    public function setFtRequestId($ftRequestId)
    {
        $this->ft_request_id = $ftRequestId;
    }

    /**
     * Get ft_request_id
     *
     * @return bigint 
     */
    public function getFtRequestId()
    {
        return $this->ft_request_id;
    }

    /**
     * Set kind
     *
     * @param string $kind
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }

    /**
     * Get kind
     *
     * @return string 
     */
    public function getKind()
    {
        return $this->kind;
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
     * Set version
     *
     * @param string $version
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
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text 
     */
    public function getBody()
    {
        return $this->body;
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
     * Set ft_request
     *
     * @param Ft\HomeBundle\Entity\FtRequest $ftRequest
     */
    public function setFtRequest(\Ft\HomeBundle\Entity\FtRequest $ftRequest)
    {
        $this->ft_request = $ftRequest;
    }

    /**
     * Get ft_request
     *
     * @return Ft\HomeBundle\Entity\FtRequest 
     */
    public function getFtRequest()
    {
        return $this->ft_request;
    }
}