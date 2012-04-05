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
     * @ORM\ManyToOne(targetEntity="Ft\HomeBundle\Entity\FtRequest")
     * @ORM\JoinColumn(name="ft_request_id", referencedColumnName="id")
     */
    private $ft_request_id;

    /**
     * @var string $namespace
     *
     * @ORM\Column(name="namespace", type="string", length=255, nullable=true)
     */
    private $namespace;

    /**
     * @var string $class_name
     *
     * @ORM\Column(name="class_name", type="string", length=255)
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
     * @ORM\Column(name="heading", type="string", length=2000, nullable=true)
     */
    private $heading;

    /**
     * @var text $detail_raw
     *
     * @ORM\Column(name="detail_raw", type="text", nullable=true)
     */
    private $detail_raw;

    /**
     * @var text $detail_html
     *
     * @ORM\Column(name="detail_html", type="text", nullable=true)
     */
    private $detail_html;

    /**
     * @var smallint $weight
     *
     * @ORM\Column(name="weight", type="smallint")
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
     * Set namespace
     *
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Get namespace
     *
     * @return string 
     */
    public function getNamespace()
    {
        return $this->namespace;
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
     * Set detail_raw
     *
     * @param text $detailRaw
     */
    public function setDetailRaw($detailRaw)
    {
        $this->detail_raw = $detailRaw;
    }

    /**
     * Get detail_raw
     *
     * @return text 
     */
    public function getDetailRaw()
    {
        return $this->detail_raw;
    }

    /**
     * Set detail_html
     *
     * @param text $detailHtml
     */
    public function setDetailHtml($detailHtml)
    {
        $this->detail_html = $detailHtml;
    }

    /**
     * Get detail_html
     *
     * @return text 
     */
    public function getDetailHtml()
    {
        return $this->detail_html;
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
}