<?php
// src/Farola/MessageBundle/Entity/ThreadMetadata.php

namespace Farola\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="frla_thread_metadata")
 */
class ThreadMetadata 
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
     *   inversedBy="metadata"
     * )
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Farola\ProfileBundle\Entity\Profile")
     */
    protected $participant;

    /**
    * @ORM\Column(name="is_read", type="boolean")
    */
    protected $isRead;

    public function __construct($thread, $participant, $isRead) {
        $this->thread = $thread;
        $this->participant = $participant;
        $this->isRead = $isRead;
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
     * Set isRead
     *
     * @param boolean $isRead
     * @return ThreadMetadata
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set thread
     *
     * @param \Farola\MessageBundle\Entity\Thread $thread
     * @return ThreadMetadata
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
     * Set participant
     *
     * @param \Farola\ProfileBundle\Entity\Profile $participant
     * @return ThreadMetadata
     */
    public function setParticipant(\Farola\ProfileBundle\Entity\Profile $participant = null)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get participant
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getParticipant()
    {
        return $this->participant;
    }
}
