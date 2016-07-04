<?php
// src/Farola/MessageBundle/Entity/Message.php

namespace Farola\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="frla_message")
 * @ORM\Entity(repositoryClass="Farola\MessageBundle\Entity\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\MessageBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Farola\ProfileBundle\Entity\Profile")
     */
    protected $sender;

    /**
     * @ORM\Column(name="body", type="text", nullable=false)   
    */
    protected $body;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    public function __construct($message, $thread, $sender) {
        $this->body = $message;
        $this->thread = $thread;
        $this->sender = $sender;
    }

    /**
    * @ORM\PrePersist   
    */
    public function creationDate()
    {
        $this->createdAt = new \DateTime;
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
     * Set thread
     *
     * @param \Farola\MessageBundle\Entity\Thread $thread
     * @return Message
     */
    public function setThread(\Farola\MessageBundle\Entity\Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \Farola\MessageBundle\Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set sender
     *
     * @param \Farola\ProfileBundle\Entity\Profile $sender
     * @return Message
     */
    public function setSender(\Farola\ProfileBundle\Entity\Profile $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Message
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
