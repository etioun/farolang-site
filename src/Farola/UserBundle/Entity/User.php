<?php
// src/Acme/UserBundle/Entity/User.php

namespace Farola\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Mapping as ORM;
use Farola\ProfileBundle\Entity\Profile;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Farola\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Profile", cascade={"persist"}, fetch="EAGER"
     * )
     * @ORM\JoinColumn(name="current_profile", nullable=true, onDelete="SET NULL")
     */
    protected $currentProfile;

    /**
     * @ORM\Column(name="pref_currency", type="string", length=3)
     */
    protected $prefCurrency;

    /**
     * @ORM\Column(name="pref_locale", type="string", length=5)
     */
    protected $prefLocale;

    /**
     * @ORM\Column(name="pref_timezome", type="string", length=50)
     */
    protected $prefTimezone;

    /**
     * @ORM\Column(name="accept_notif", type="boolean")
     */
    protected $acceptNotification;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="last_mail_notif_at", type="datetime")
     */
    protected $lastMailNotificationAt;  

    public function __construct()
    {
        parent::__construct();
        
        $this->lastMailNotificationAt = new \DateTime();
        $this->prefLocale = 'en';
        $this->prefCurrency = 'EUR';
        $this->prefTimezone = "Europe/Paris";
        $this->acceptNotification = true;
    }

    /**
    * @ORM\PrePersist   
    */
    public function createProfile()
    {
        if ($this->currentProfile == null) {
            $this->currentProfile = new Profile($this);
        }
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
     * Set prefCurrency
     *
     * @param string $prefCurrency
     * @return User
     */
    public function setPrefCurrency($prefCurrency)
    {
        $this->prefCurrency = $prefCurrency;

        return $this;
    }

    /**
     * Get prefCurrency
     *
     * @return string 
     */
    public function getPrefCurrency()
    {
        return $this->prefCurrency;
    }

    /**
     * Set prefLocale
     *
     * @param string $prefLocale
     * @return User
     */
    public function setPrefLocale($prefLocale)
    {
        $this->prefLocale = $prefLocale;

        return $this;
    }

    /**
     * Get prefLocale
     *
     * @return string 
     */
    public function getPrefLocale()
    {
        return $this->prefLocale;
    }

    /**
     * Set prefTimezone
     *
     * @param string $prefTimezone
     * @return User
     */
    public function setPrefTimezone($prefTimezone)
    {
        $this->prefTimezone = $prefTimezone;

        return $this;
    }

    /**
     * Get prefTimezone
     *
     * @return string 
     */
    public function getPrefTimezone()
    {
        return $this->prefTimezone;
    }

    /**
     * Set currentProfile
     *
     * @param \Farola\ProfileBundle\Entity\Profile $currentProfile
     * @return User
     */
    public function setCurrentProfile(\Farola\ProfileBundle\Entity\Profile $currentProfile = null)
    {
        $this->currentProfile = $currentProfile;

        return $this;
    }

    /**
     * Get currentProfile
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getCurrentProfile()
    {
        return $this->currentProfile;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
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
     * Set lastMailNotificationAt
     *
     * @param \DateTime $lastMailNotificationAt
     * @return User
     */
    public function setLastMailNotificationAt($lastMailNotificationAt)
    {
        $this->lastMailNotificationAt = $lastMailNotificationAt;

        return $this;
    }

    /**
     * Get lastMailNotificationAt
     *
     * @return \DateTime 
     */
    public function getLastMailNotificationAt()
    {
        return $this->lastMailNotificationAt;
    }

    /**
     * Set acceptNotification
     *
     * @param boolean $acceptNotification
     * @return User
     */
    public function setAcceptNotification($acceptNotification)
    {
        $this->acceptNotification = $acceptNotification;

        return $this;
    }

    /**
     * Get acceptNotification
     *
     * @return boolean 
     */
    public function getAcceptNotification()
    {
        return $this->acceptNotification;
    }
}
