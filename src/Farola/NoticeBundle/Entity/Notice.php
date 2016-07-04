<?php

namespace Farola\NoticeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

 /**
 * @ORM\Entity
 * @ORM\Table(name="frla_notice")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass="Farola\NoticeBundle\Entity\NoticeRepository")
 */
class Notice
{
    const CAT_TEACHING = 0;
    const CAT_STUDENT = 1;
    const CAT_LANG_EX = 2;
    const CAT_OTHER = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\ProfileBundle\Entity\Profile", inversedBy="notices"
     * )    
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id",nullable=false)
     */
    protected $profile;

    /**
     * @ORM\Column(name="category", type="integer")   
    */
    protected $category;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\Column(name="activated", type="boolean")
     */
    protected $activated;

    /**
     * @ORM\Column(name="online_service", type="boolean")   
    */
    protected $onlineService;

    /**
     * @ORM\Column(name="online_method", type="string", length=2, nullable=true)   
    */
    protected $onlineMethod;


    /**
     * @ORM\Column(name="tags", type="simple_array",nullable=true)   
    */
    protected $tags;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)   
    */
    protected $description;

    /**
     * @ORM\Column(name="teached_language", type="simple_array",nullable=true)   
    */
    protected $teachedLanguage;

    /**
     * @ORM\Column(name="learned_language", type="string", length=10, nullable=true)   
    */
    protected $learnedLanguage;

    /**
     * @ORM\Column(name="local_price", type="float",nullable=true)   
    */
    protected $localPrice;

    /**
     * @ORM\Column(name="local_currency", type="string", length=10,nullable=true)
     * @Assert\Currency
    */
    protected $localCurrency;

    /**
     * @ORM\Column(name="euro_price", type="float",nullable=true)   
    */
    protected $euroPrice; 

    /**
     * @ORM\Column(name="near_location", type="boolean")   
    */
    protected $nearLocation;

    /**
     * @ORM\Column(name="address",type="string", length=255,nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(name="country_code",type="string", length=10, nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(name="latitude",type="decimal", precision=9,scale=6,nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(name="longitude",type="decimal", precision=9,scale=6,nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(name="place_id",type="string", length=255,nullable=true)
     */
    protected $placeId;

    /**
     * @ORM\Column(name="avail_anytime", type="boolean")   
    */
    protected $availableAnytime;

    /**
     * @ORM\Column(name="availabilities", type="json_array", nullable=true)
     * @Assert\Count(max = 21, maxMessage="Too many availability periods")
    */
    protected $availabilities;

    /**
     * @ORM\Column(name="timezone", type="string", length=50)
    */
    protected $timezone;

    public function __construct($profile, $category){
        $this->onlineService = true;
        $this->nearLocation = true;
        $this->activated = true;
        $this->profile = $profile;
        $this->availableAnytime = true;
        $this->timezone = $profile->getUser()->getPrefTimezone();
        $this->category = $category;
        $this->localCurrency = $profile->getUser()->getPrefCurrency();
        // $this->teachedLanguage = array('-'.$profile->getUser()->getPrefLocale());
        $this->address = $profile->getAddress();
        $this->longitude = $profile->getLongitude();
        $this->latitude = $profile->getLatitude();
        $this->country = $profile->getCountry();
        $this->placeId = $profile->getPlaceId();
        $this->localPrice = 0;
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
     * Set tags
     *
     * @param array $tags
     * @return Notice
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Notice
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set teachedLanguage
     *
     * @param string $teachedLanguage
     * @return Notice
     */
    public function setTeachedLanguage($teachedLanguage)
    {
        $this->teachedLanguage = $teachedLanguage;

        return $this;
    }

    /**
     * Get teachedLanguage
     *
     * @return string 
     */
    public function getTeachedLanguage()
    {
        return $this->teachedLanguage;
    }

    /**
     * Set learnedLanguage
     *
     * @param string $learnedLanguage
     * @return Notice
     */
    public function setLearnedLanguage($learnedLanguage)
    {
        $this->learnedLanguage = $learnedLanguage;

        return $this;
    }

    /**
     * Get learnedLanguage
     *
     * @return string 
     */
    public function getLearnedLanguage()
    {
        return $this->learnedLanguage;
    }

    /**
     * Set localPrice
     *
     * @param float $localPrice
     * @return Notice
     */
    public function setLocalPrice($localPrice)
    {
        $this->localPrice = $localPrice;

        return $this;
    }

    /**
     * Get localPrice
     *
     * @return float 
     */
    public function getLocalPrice()
    {
        return $this->localPrice;
    }

    /**
     * Set localCurrency
     *
     * @param string $localCurrency
     * @return Notice
     */
    public function setLocalCurrency($localCurrency)
    {
        $this->localCurrency = $localCurrency;

        return $this;
    }

    /**
     * Get localCurrency
     *
     * @return string 
     */
    public function getLocalCurrency()
    {
        return $this->localCurrency;
    }

    /**
     * Set euroPrice
     *
     * @param float $euroPrice
     * @return Notice
     */
    public function setEuroPrice($euroPrice)
    {
        $this->euroPrice = $euroPrice;

        return $this;
    }

    /**
     * Get euroPrice
     *
     * @return float 
     */
    public function getEuroPrice()
    {
        return $this->euroPrice;
    }

    
    /**
     * Set onlineService
     *
     * @param boolean $onlineService
     * @return Notice
     */
    public function setOnlineService($onlineService)
    {
        $this->onlineService = $onlineService;

        return $this;
    }

    /**
     * Get onlineService
     *
     * @return boolean 
     */
    public function getOnlineService()
    {
        return $this->onlineService;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Notice
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Notice
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Notice
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Notice
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set placeId
     *
     * @param string $placeId
     * @return Notice
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;

        return $this;
    }

    /**
     * Get placeId
     *
     * @return string 
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * Set onlineMethod
     *
     * @param integer $onlineMethod
     * @return Notice
     */
    public function setOnlineMethod($onlineMethod)
    {
        $this->onlineMethod = $onlineMethod;

        return $this;
    }

    /**
     * Get onlineMethod
     *
     * @return integer 
     */
    public function getOnlineMethod()
    {
        return $this->onlineMethod;
    }

    /**
     * Set profile
     *
     * @param \Farola\ProfileBundle\Entity\Profile $profile
     * @return Notice
     */
    public function setProfile(\Farola\ProfileBundle\Entity\Profile $profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Farola\ProfileBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }



    /**
     * Set availableAnytime
     *
     * @param boolean $availableAnytime
     * @return Notice
     */
    public function setAvailableAnytime($availableAnytime)
    {
        $this->availableAnytime = $availableAnytime;

        return $this;
    }

    /**
     * Get availableAnytime
     *
     * @return boolean 
     */
    public function getAvailableAnytime()
    {
        return $this->availableAnytime;
    }

    /**
     * Set availabilities
     *
     * @param array $availabilities
     * @return Notice
     */
    public function setAvailabilities($availabilities)
    {
        $this->availabilities = $availabilities;

        return $this;
    }

    /**
     * Get availabilities
     *
     * @return array 
     */
    public function getAvailabilities()
    {
        return $this->availabilities;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return Notice
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set activated
     *
     * @param boolean $activated
     * @return Notice
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return boolean 
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set nearLocation
     *
     * @param boolean $nearLocation
     *
     * @return Notice
     */
    public function setNearLocation($nearLocation)
    {
        $this->nearLocation = $nearLocation;

        return $this;
    }

    /**
     * Get nearLocation
     *
     * @return boolean
     */
    public function getNearLocation()
    {
        return $this->nearLocation;
    }
}
