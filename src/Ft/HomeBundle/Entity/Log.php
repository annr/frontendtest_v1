<?php

namespace Ft\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\HomeBundle\Entity\Log
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Log
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
 	 * @ORM\ManyToOne(targetEntity="FtRequest", inversedBy="logs")
	 * @ORM\JoinColumn(name="ft_request_id", referencedColumnName="id")
    */
    protected $ft_request;

    /**
     * @var text $data
     *
     * @ORM\Column(name="data", type="text")
     */
    private $data;

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
     * Set data
     *
     * @param text $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get data
     *
     * @return text 
     */
    public function getData()
    {
        return $this->data;
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