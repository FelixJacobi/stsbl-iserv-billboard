<?php
// src/Stsbl/BillBoardBundle/Entity/Entry.php
namespace Stsbl\BillBoardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use IServ\CrudBundle\Entity\CrudInterface;
use IServ\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BillBoardBundle:Entry
 *
 * @author Felix Jacobi <felix.jacobi@stsbl.de>
 * @license GNU General Public License <http://gnu.org/licenses/gpl-3.0>
 * @ORM\Entity
 * @ORM\Table(name="billboard")
 * @ORM\HasLifecycleCallbacks
 */
class Entry implements CrudInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @var int
     */
    private $id;
    
    /**
     * @ORM\Column(name="title",type="text",length=255)
     * @Assert\NotBlank()
     * 
     * @var string
     */
    private $title;
    
    /**
     * @ORM\Column(name="description",type="text")
     * @Assert\NotBlank()
     * 
     * @var string
     */
    private $description;
    
    /**
     * @ORM\Column(name="time",type="datetime",nullable=false)
     * 
     * @var \DateTime
     */
    private $time;
    
    /**
     * @ORM\Column(name="updated_at",type="datetime",nullable=false)
     * 
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="\Stsbl\BillBoardBundle\Entity\Category", fetch="EAGER")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     *
     * @Assert\NotNull()
     *
     * @var Category
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="\IServ\CoreBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="author", referencedColumnName="act")
     *
     * @var User
     */
    private $author;
    
    /**
     * @ORM\Column(name="visible",type="boolean")
     * 
     * @var boolean
     */ 
    private $visible;
    
        /**
     * @ORM\OneToMany(targetEntity="EntryImage", mappedBy="entry")
     *
     * @var ArrayCollection
     */
    private $images;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }
    
    /**
     * Lifecycle callback to set the creation date
     *
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->setTime(new \DateTime("now"));
        $this->updateLastUpdatedTime();
    }
    
    /**
     * Lifecycle callback to set the update date
     * 
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->updateLastUpdatedTime();
    }

    /**
     * Updates last updated time to 'now'
     */
    public function updateLastUpdatedTime()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }
    
    /**
     * Returns a human readable string
     * 
     * @return string
     */
    public function __toString()
    {
        return (string)$this->title;
    }
    
    /**
     * Get id
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get time
     * 
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
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
     * Get category
     * 
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Get author
     * 
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    /**
     * Get visible
     * 
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }
    
    /**
     * Get images
     * 
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * Set title
     * 
     * @param string $title
     * 
     * @return Entry
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * Set description
     * 
     * @param string $description
     * 
     * @return Entry
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * Set time
     * 
     * @param \DateTime $time
     * 
     * @return Entry
     */
    public function setTime(\DateTime $time = null)
    {
        $this->time = $time;
        
        return $this;
    }
    
    /**
     * Set updatedAt
     * 
     * @param \DateTime $updatedAt
     * 
     * @return Entry
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * Set category
     * 
     * @param Category $category
     * 
     * @return Entry
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
        
        return $this;
    }
    
    /**
     * Set author
     * 
     * @param User $author
     * 
     * @return Entry
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        
        return $this;
    }
    
    /**
     * Set visible
     * 
     * @param boolean $visible
     * 
     * @return Entry
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        
        return $this;
    }
    
    /**
     * Set images
     * 
     * @param ArrayCollection $images
     * 
     * @return Entry
     */
    public function setImages(ArrayCollection $images)
    {
        $this->images = $images;
        
        return $this;
    }
    
    /**
     * Checks if the author is valid. i.e. he isn't deleted
     * 
     * @return boolean
     */
    public function hasValidAuthor()
    {
        try {
            return $this->author->id;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns a displayable author. Performs an exists check
     * 
     * @return string|User
     */
    public function getAuthorDisplay()
    {
        return $this->hasValidAuthor() ? $this->getAuthor() : '?';
    }
}