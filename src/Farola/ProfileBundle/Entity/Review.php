<?php
// src/Farola/MessageBundle/Entity/Message.php

namespace Farola\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="frla_review")
  * @ORM\HasLifecycleCallbacks()
  * @ORM\Entity(repositoryClass="Farola\ProfileBundle\Entity\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Profile"
     * )
     */
    protected $writer;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Profile", inversedBy="reviewsReceived"
     * )    
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id",nullable=false)
     * )
     */
    protected $subject;

    /**
     * @ORM\Column(name="review",type="text")
     */
    protected $review;

    /**
     * @ORM\Column(name="rating",type="integer")
     */
    protected $rating;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\OneToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Review", cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="back_review_id", nullable=true, onDelete="SET NULL")
     */ 
    protected $backReview;

    /**
    * @ORM\PrePersist   
    */
    public function createDate()
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
     * Set review
     *
     * @param string $review
     * @return Review
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set writer
     *
     * @param \Farola\ProfileBundle\Entity\Profile $writer
     * @return Review
     */
    public function setWriter(\Farola\ProfileBundle\Entity\Profile $writer = null)
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Get writer
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Set subject
     *
     * @param \Farola\ProfileBundle\Entity\Profile $subject
     * @return Review
     */
    public function setSubject(\Farola\ProfileBundle\Entity\Profile $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set backReview
     *
     * @param \Farola\ProfileBundle\Entity\Review $backReview
     * @return Review
     */
    public function setBackReview(\Farola\ProfileBundle\Entity\Review $backReview = null)
    {
        $this->backReview = $backReview;

        return $this;
    }

    /**
     * Get backReview
     *
     * @return \Farola\ProfileBundle\Entity\Review 
     */
    public function getBackReview()
    {
        return $this->backReview;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Review
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
