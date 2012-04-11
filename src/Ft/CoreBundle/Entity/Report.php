<?php

namespace Ft\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\CoreBundle\Entity\Report
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Report
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
     * @var text $html
     *
     * @ORM\Column(name="html", type="text")
     */
    private $html;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

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
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
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
}