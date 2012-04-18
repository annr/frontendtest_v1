<?php

namespace Ft\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\HomeBundle\Entity\FtRequest
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FtRequest
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
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=1000)
     */
    private $url;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=16, nullable=true)
     */
    private $ip;

    /**
     * @var string $environment
     *
     * @ORM\Column(name="environment", type="string", length=200, nullable=true)
     */
    private $environment;

    /**
     * @var string $notes
     *
     * @ORM\Column(name="notes", type="string", length=1000, nullable=true)
     */
    private $notes;

    /**
     * @var boolean $updates_req
     *
     * @ORM\Column(name="updates_req", type="boolean", nullable=true)
     */
    private $updates_req;

    /**
     * @var boolean $moretests_req
     *
     * @ORM\Column(name="moretests_req", type="boolean", nullable=true)
     */
    private $moretests_req;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=4)
     */
    private $type;

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
     * @var datetime $delivered
     *
     * @ORM\Column(name="delivered", type="datetime", nullable=true)
     */
    private $delivered;


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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set ip
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set environment
     *
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get environment
     *
     * @return string 
     */
    public function getEnvironment()
    {
        return $this->environment;
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
     * Set updates_req
     *
     * @param boolean $updatesReq
     */
    public function setUpdatesReq($updatesReq)
    {
        $this->updates_req = $updatesReq;
    }

    /**
     * Get updates_req
     *
     * @return boolean 
     */
    public function getUpdatesReq()
    {
        return $this->updates_req;
    }

    /**
     * Set moretests_req
     *
     * @param boolean $moretestsReq
     */
    public function setMoretestsReq($moretestsReq)
    {
        $this->moretests_req = $moretestsReq;
    }

    /**
     * Get moretests_req
     *
     * @return boolean 
     */
    public function getMoretestsReq()
    {
        return $this->moretests_req;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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

    /**
     * Set delivered
     *
     * @param datetime $delivered
     */
    public function setDelivered($delivered)
    {
        $this->delivered = $delivered;
    }

    /**
     * Get delivered
     *
     * @return datetime 
     */
    public function getDelivered()
    {
        return $this->delivered;
    }

    public function getResults()
    {
        return '10';
    }

    public function __toString()
    {
        return $this->getUrl() . ': ' . $this->getEmail();
    }


}