<?php

namespace Farola\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="frla_relation")
 */
class Relation
{
    const TYPE_CONTACT = 0;
    const TYPE_IGNORE = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Profile", inversedBy="relations"
     * )
     * @ORM\JoinColumn(name="rel_owner_id", referencedColumnName="id",nullable=false)
     */
    protected $relOwner;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Profile"
     * )
     */
    protected $relatedProfile;

    /**
     * @ORM\Column(name="type",type="integer")
     */
    protected $relationType;

    public function __construct($relOwner, $relatedProfile, $relationType)
    {
        $this->relOwner = $relOwner;
        $this->relatedProfile = $relatedProfile;
        $this->relationType = $relationType;
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
     * Set relationType
     *
     * @param integer $relationType
     * @return Relation
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;

        return $this;
    }

    /**
     * Get relationType
     *
     * @return integer 
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * Set relOwner
     *
     * @param \Farola\ProfileBundle\Entity\Profile $relOwner
     * @return Relation
     */
    public function setRelOwner(\Farola\ProfileBundle\Entity\Profile $relOwner)
    {
        $this->relOwner = $relOwner;

        return $this;
    }

    /**
     * Get relOwner
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getRelOwner()
    {
        return $this->relOwner;
    }

    /**
     * Set relatedProfile
     *
     * @param \Farola\ProfileBundle\Entity\Profile $relatedProfile
     * @return Relation
     */
    public function setRelatedProfile(\Farola\ProfileBundle\Entity\Profile $relatedProfile = null)
    {
        $this->relatedProfile = $relatedProfile;

        return $this;
    }

    /**
     * Get relatedProfile
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getRelatedProfile()
    {
        return $this->relatedProfile;
    }
}
