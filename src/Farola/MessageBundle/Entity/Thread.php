<?php
// src/Farola/MessageBundle/Entity/Thread.php

namespace Farola\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="frla_thread")
 * @ORM\Entity(repositoryClass="Farola\MessageBundle\Entity\ThreadRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Thread 
{
    const CAT_GENERAL = 0;
    const CAT_NOTICE = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\NoticeBundle\Entity\Notice"
     * )
     */
    protected $notice;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Farola\MessageBundle\Entity\Message",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Farola\MessageBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"},
     *   fetch="EAGER"  
     * )
     */
    protected $metadata;

    /**
    * @ORM\Column(name="last_message_at", type="datetime", nullable=true)
    */
    protected $lastMessageAt;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Profile")
    */
    protected $lastSender;

    /**
    * @ORM\Column(name="last_msg_body_short", type="string", length=100, nullable=true)
    */
    protected $lastMessageBodyShort;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->metadata = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
    * @ORM\PreUpdate
    * @ORM\PrePersist   
    */
    public function updateDate()
    {
        $this->lastMessageAt = new \DateTime;
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
     * @param integer $category
     * @return Thread
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set lastMessageAt
     *
     * @param \DateTime $lastMessageAt
     * @return Thread
     */
    public function setLastMessageAt($lastMessageAt)
    {
        $this->lastMessageAt = $lastMessageAt;

        return $this;
    }

    /**
     * Get lastMessageAt
     *
     * @return \DateTime 
     */
    public function getLastMessageAt()
    {
        return $this->lastMessageAt;
    }

    /**
     * Set lastMessageBodyShort
     *
     * @param string $lastMessageBodyShort
     * @return Thread
     */
    public function setLastMessageBodyShort($lastMessageBodyShort)
    {
        $this->lastMessageBodyShort = $lastMessageBodyShort;

        return $this;
    }

    /**
     * Get lastMessageBodyShort
     *
     * @return string 
     */
    public function getLastMessageBodyShort()
    {
        return $this->lastMessageBodyShort;
    }

    /**
     * Set notice
     *
     * @param \Farola\NoticeBundle\Entity\Notice $notice
     * @return Thread
     */
    public function setNotice(\Farola\NoticeBundle\Entity\Notice $notice = null)
    {
        $this->notice = $notice;

        return $this;
    }

    /**
     * Get notice
     *
     * @return \Farola\NoticeBundle\Entity\Notice 
     */
    public function getNotice()
    {
        return $this->notice;
    }

    /**
     * Add messages
     *
     * @param \Farola\MessageBundle\Entity\Message $messages
     * @return Thread
     */
    public function addMessage(\Farola\MessageBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Farola\MessageBundle\Entity\Message $messages
     */
    public function removeMessage(\Farola\MessageBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add metadata
     *
     * @param \Farola\MessageBundle\Entity\ThreadMetadata $metadata
     * @return Thread
     */
    public function addMetadatum(\Farola\MessageBundle\Entity\ThreadMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \Farola\MessageBundle\Entity\ThreadMetadata $metadata
     */
    public function removeMetadatum(\Farola\MessageBundle\Entity\ThreadMetadata $metadata)
    {
        $this->metadata->removeElement($metadata);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set lastSender
     *
     * @param \Farola\ProfileBundle\Entity\Profile $lastSender
     * @return Thread
     */
    public function setLastSender(\Farola\ProfileBundle\Entity\Profile $lastSender = null)
    {
        $this->lastSender = $lastSender;

        return $this;
    }

    /**
     * Get lastSender
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getLastSender()
    {
        return $this->lastSender;
    }
}
