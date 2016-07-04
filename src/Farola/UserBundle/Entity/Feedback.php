<?php

namespace Farola\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

 /**
 * @ORM\Entity
 * @ORM\Table(name="frla_feedback")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Feedback
{
    const CAT_NEED_HELP = 0;
    const CAT_BUG_REPORT = 1;
    const CAT_IDEA = 2;
    const CAT_ENQUIRY = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\UserBundle\Entity\User"
     * )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(name="category", type="integer")   
    */
    protected $category;

    /**
     * @ORM\Column(name="summary", type="string", length=255)   
    */
    protected $summary;

    /**
     * @ORM\Column(name="detail", type="text")   
    */
    protected $detail;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    

    public function __construct($user){
        $this->user = $user;
    }

    /**
    * @ORM\PreUpdate
    * @ORM\PrePersist   
    */
    public function updateDate()
    {
        $this->updatedAt = new \DateTime;
    }

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
     * Set category
     *
     * @param string $category
     * @return Notice
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Notice
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Notice
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Feedback
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return Feedback
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set user
     *
     * @param \Farola\UserBundle\Entity\User $user
     * @return Feedback
     */
    public function setUser(\Farola\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Farola\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
