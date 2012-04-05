<?php

namespace Ft\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\HomeBundle\Entity\FreeProductReportRecord
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FreeProductReportRecord
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
     * @var text $config
     *
     * @ORM\Column(name="config", type="text", nullable=true)
     */
    private $config;

    /**
     * @var text $raw_text
     *
     * @ORM\Column(name="raw_text", type="text", nullable=true)
     */
    private $raw_text;

    /**
     * @var text $html
     *
     * @ORM\Column(name="html", type="text", nullable=true)
     */
    private $html;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
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
     * Set config
     *
     * @param text $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get config
     *
     * @return text 
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set raw_text
     *
     * @param text $rawText
     */
    public function setRawText($rawText)
    {
        $this->raw_text = $rawText;
    }

    /**
     * Get raw_text
     *
     * @return text 
     */
    public function getRawText()
    {
        return $this->raw_text;
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
}