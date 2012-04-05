<?php

namespace Ft\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ft\HomeBundle\Entity\FreeProduct
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FreeProduct
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
     * @ORM\ManyToOne(targetEntity="FtRequest")
     * @ORM\JoinColumn(name="ft_request_id", referencedColumnName="id")
     */
    private $ft_request_id;

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
     * @var string $notes
     *
     * @ORM\Column(name="notes", type="string", length=1000, nullable=true)
     */
    private $notes;

    /**
     * @var boolean $upsold
     *
     * @ORM\Column(name="upsold", type="boolean", nullable=true)
     */
    private $upsold;

    /**
     * @var smallint $issues_total
     *
     * @ORM\Column(name="issues_total", type="smallint", nullable=true)
     */
    private $issues_total;

    /**
     * @var integer $issues_total_weight
     *
     * @ORM\Column(name="issues_total_weight", type="integer", nullable=true)
     */
    private $issues_total_weight;

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
     * Set uphold
     *
     * @param boolean $uphold
     */
    public function setUphold($uphold)
    {
        $this->uphold = $uphold;
    }

    /**
     * Get uphold
     *
     * @return boolean 
     */
    public function getUphold()
    {
        return $this->uphold;
    }

    /**
     * Set issues_total
     *
     * @param smallint $issuesTotal
     */
    public function setIssuesTotal($issuesTotal)
    {
        $this->issues_total = $issuesTotal;
    }

    /**
     * Get issues_total
     *
     * @return smallint 
     */
    public function getIssuesTotal()
    {
        return $this->issues_total;
    }

    /**
     * Set issues_total_weight
     *
     * @param integer $issuesTotalWeight
     */
    public function setIssuesTotalWeight($issuesTotalWeight)
    {
        $this->issues_total_weight = $issuesTotalWeight;
    }

    /**
     * Get issues_total_weight
     *
     * @return integer 
     */
    public function getIssuesTotalWeight()
    {
        return $this->issues_total_weight;
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
