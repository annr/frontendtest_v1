<?php

namespace Ft\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\HomeBundle\Entity\HtmlRecord
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class HtmlRecord
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
     * @var integer $ft_request_id
     *
     * @ORM\Column(name="ft_request_id", type="integer")
     */
    private $ft_request_id;

    /**
     * @var FtRequest $ft_request
 	 * @ORM\ManyToOne(targetEntity="FtRequest", inversedBy="logs")
	 * @ORM\JoinColumn(name="ft_request_id", referencedColumnName="id")
    */
    protected $ft_request;

    /**
     * @var text $html
     *
     * @ORM\Column(name="html", type="text")
     */
    private $html;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


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
     * @param integer $ftRequestId
     */
    public function setFtRequestId($ftRequestId)
    {
        $this->ft_request_id = $ftRequestId;
    }

    /**
     * Get ft_request_id
     *
     * @return integer 
     */
    public function getFtRequestId()
    {
        return $this->ft_request_id;
    }

    /**
     * Set html
     *
     * @param text $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * Get html
     *
     * @return text 
     */
    public function getHtml()
    {
        return $this->html;
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