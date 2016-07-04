<?php

namespace Farola\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="frla_profile")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Farola\ProfileBundle\Entity\ProfileRepository")
 */
class Profile
{
    const DEFAULT_PROF_PIC_FN = "default.jpg";


    /**
     * @ORM\Id
     * @ORM\Column(name="id",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Farola\UserBundle\Entity\User"
     * )
     */
    protected $user;

    /**
     * @ORM\Column(name="name", type="string", length=25)   
    */
    protected $name;

    /**
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=true)   
    */
    protected $dateOfBirth;

    /**
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)   
    */
    protected $gender;

    /**
     * @ORM\Column(name="origin_country_code", type="string", length=10, nullable=true) 
     */
    protected $countryOfOrigin;

    /**
     * @ORM\Column(name="profile_picture_filename", type="string", length=255, nullable=true)  
     */
    protected $profilePictureFilename;

    /**
     * @ORM\Column(name="spokenLanguages", type="simple_array", nullable=true)   
     */
    protected $spokenLanguages;

    /**
     * @ORM\Column(name="aboutMe", type="text", nullable=true)   
     */
    protected $aboutMe;

    /**
     * @ORM\Column(name="experience", type="text", nullable=true)   
     */
    protected $experience;

     /**
     * @ORM\Column(name="interests", type="text", nullable=true)   
     */
    protected $interests;

    /**
     * @ORM\Column(name="pers_video_lnk", type="string", length=255, nullable=true) 
     * )
     */
    protected $personalVideoLink;

    /**
     * @ORM\Column(name="weblinks", type="json_array", nullable=true) 
     */
    protected $weblinks;

   /**
     * @ORM\Column(name="address",type="string", length=255,nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(name="current_country_code",type="string", length=10, nullable=true)
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
     * @ORM\OneToMany(
     *   targetEntity="Farola\ProfileBundle\Entity\Relation", mappedBy="relOwner"
     * )
     */
    protected $relations;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Farola\NoticeBundle\Entity\Notice", mappedBy="profile"
     * )
     */
    protected $notices;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Farola\ProfileBundle\Entity\Review", mappedBy="subject"
     * )
     */
    protected $reviewsReceived;

    /**
     * @ORM\Column(name="agg_review_count", type="integer")
     */
    protected $aggReviewCount;

    /**
     * Constructor
     */
    public function __construct($user)
    {
        $this->spokenLanguages = null;
        $this->profilePictureFilename = Profile::DEFAULT_PROF_PIC_FN;
        $this->user = $user;
        $this->name = ucfirst($user->getUsername());
        $this->relations = new ArrayCollection();
        $this->aggReviewCount = 0;
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
     * Set name
     *
     * @param string $name
     * @return Profile
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     * @return Profile
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime 
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set profilePictureFilename
     *
     * @param string $profilePictureFilename
     * @return Profile
     */
    public function setProfilePictureFilename($profilePictureFilename)
    {
        if ($profilePictureFilename == null) {
            $this->profilePictureFilename = Profile::DEFAULT_PROF_PIC_FN;
        } else {
           $this->profilePictureFilename = $profilePictureFilename; 
        }

        return $this;
    }

    /**
     * Get profilePictureFilename
     *
     * @return string 
     */
    public function getProfilePictureFilename()
    {
        return $this->profilePictureFilename;
    }

    /**
     * Set personalVideoLink
     *
     * @param string $personalVideoLink
     * @return Profile
     */
    public function setPersonalVideoLink($personalVideoLink)
    {
        $this->personalVideoLink = $personalVideoLink;

        return $this;
    }

    /**
     * Get personalVideoLink
     *
     * @return string 
     */
    public function getPersonalVideoLink()
    {
        return $this->personalVideoLink;
    }

   

    /**
     * Set user
     *
     * @param \Farola\UserBundle\Entity\User $user
     * @return Profile
     */
    public function setUser(\Farola\UserBundle\Entity\User $user = null)
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

    /**
     * Set countryOfOrigin
     *
     * @param string $countryOfOrigin
     * @return Profile
     */
    public function setCountryOfOrigin($countryOfOrigin = null)
    {
        $this->countryOfOrigin = $countryOfOrigin;

        return $this;
    }

    /**
     * Get countryOfOrigin
     *
     * @return string 
     */
    public function getCountryOfOrigin()
    {
        return $this->countryOfOrigin;
    }

    /**
     * Set spokenLanguages
     *
     * @param array $spokenLanguages
     * @return Profile
     */
    public function setSpokenLanguages($spokenLanguages)
    {
        $this->spokenLanguages = $spokenLanguages;

        return $this;
    }

    /**
     * Get spokenLanguages
     *
     * @return array 
     */
    public function getSpokenLanguages()
    {
        return $this->spokenLanguages;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Profile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    
    /**
     * Set weblinks
     *
     * @param array $weblinks
     * @return Profile
     */
    public function setWeblinks($weblinks)
    {
        $this->weblinks = $weblinks;

        return $this;
    }

    /**
     * Get weblinks
     *
     * @return array 
     */
    public function getWeblinks()
    {
        return $this->weblinks;
    }

    /**
     * Set aboutMe
     *
     * @param string $aboutMe
     * @return Profile
     */
    public function setAboutMe($aboutMe)
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    /**
     * Get aboutMe
     *
     * @return string 
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * Set experience
     *
     * @param string $experience
     * @return Profile
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string 
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set interests
     *
     * @param string $interests
     * @return Profile
     */
    public function setInterests($interests)
    {
        $this->interests = $interests;

        return $this;
    }

    /**
     * Get interests
     *
     * @return string 
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Profile
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
     * Add notices
     *
     * @param \Farola\NoticeBundle\Entity\Notice $notices
     * @return Profile
     */
    public function addNotice(\Farola\NoticeBundle\Entity\Notice $notices)
    {
        $this->notices[] = $notices;

        return $this;
    }

    /**
     * Remove notices
     *
     * @param \Farola\NoticeBundle\Entity\Notice $notices
     */
    public function removeNotice(\Farola\NoticeBundle\Entity\Notice $notices)
    {
        $this->notices->removeElement($notices);
    }

    /**
     * Get notices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotices()
    {
        return $this->notices;
    }

    /**
     * Add reviewsReceived
     *
     * @param \Farola\ProfileBundle\Entity\Review $reviewsReceived
     * @return Profile
     */
    public function addReviewsReceived(\Farola\ProfileBundle\Entity\Review $reviewsReceived)
    {
        $this->reviewsReceived[] = $reviewsReceived;

        return $this;
    }

    /**
     * Remove reviewsReceived
     *
     * @param \Farola\ProfileBundle\Entity\Review $reviewsReceived
     */
    public function removeReviewsReceived(\Farola\ProfileBundle\Entity\Review $reviewsReceived)
    {
        $this->reviewsReceived->removeElement($reviewsReceived);
    }

    /**
     * Get reviewsReceived
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviewsReceived()
    {
        return $this->reviewsReceived;
    }

    /**
     * Set aggReviewCount
     *
     * @param integer $aggReviewCount
     * @return Profile
     */
    public function setAggReviewCount($aggReviewCount)
    {
        $this->aggReviewCount = $aggReviewCount;

        return $this;
    }

    /**
     * Get aggReviewCount
     *
     * @return integer 
     */
    public function getAggReviewCount()
    {
        return $this->aggReviewCount;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
}
