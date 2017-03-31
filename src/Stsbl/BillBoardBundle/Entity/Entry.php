<?php
// src/Stsbl/BillBoardBundle/Entity/Entry.php
namespace Stsbl\BillBoardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use IServ\CrudBundle\Entity\CrudInterface;
use IServ\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/*
 * The MIT License
 *
 * Copyright 2017 Felix Jacobi.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * BillBoardBundle:Entry
 *
 * @author Felix Jacobi <felix.jacobi@stsbl.de>
 * @license MIT license <https://opensource.org/licenses/MIT>
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
     * @ORM\Column(name="visible", type="boolean")
     * 
     * @var boolean
     */ 
    private $visible;
    
    /**
     * @ORM\Column(name="closed", type="boolean")
     * 
     * @var boolean
     */
    private $closed;
    
    /**
     * @ORM\OneToMany(targetEntity="EntryImage", mappedBy="entry")
     *
     * @var ArrayCollection
     */
    private $images;
    
    /**
     * @ORM\OneToMany(targetEntity="EntryComment", mappedBy="entry")
     * 
     * @var ArrayCollection
     */
    private $comments;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * Get comments
     * 
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
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
     * Set comments
     * 
     * @param ArrayCollection $comments
     * 
     * @return Entry
     */
    public function setComments(ArrayCollection $comments)
    {
        $this->comments = $comments;
        
        return $this;
    }


    /**
     * Checks if the author is valid. i.e. he isn't deleted
     * 
     * @return boolean
     */
    public function hasValidAuthor()
    {
        return $this->author != null;
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

    /**
     * Set closed
     *
     * @param boolean $closed
     *
     * @return Entry
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Add image
     *
     * @param EntryImage $image
     *
     * @return Entry
     */
    public function addImage(EntryImage $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param EntryImage $image
     */
    public function removeImage(EntryImage $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Add comment
     *
     * @param EntryComment $comment
     *
     * @return Entry
     */
    public function addComment(EntryComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param EntryComment $comment
     */
    public function removeComment(EntryComment $comment)
    {
        $this->comments->removeElement($comment);
    }
}
