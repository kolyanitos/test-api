<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ODM\Document(collection="images", repositoryClass="AppBundle\Repository\ImageRepository")
 * @Vich\Uploadable
 * @ODM\HasLifecycleCallbacks
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Image implements \JsonSerializable
{
    const IMAGE_DIR = '/uploads/images/';

    /**
     * @ODM\Id
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"images"})
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     *
     * @var string $imageName
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"images"})
     */
    protected $imageName;

    /**
     * @var File $imageFile
     * @Assert\NotNull(message="You need to upload image")
     * @Assert\Image()
     * @Vich\UploadableField(mapping="images", fileNameProperty="imageName")
     */
    protected $imageFile;

    /**
     * @var \DateTime $created
     *
     * @ODM\Field(type="date")
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("DateTime")
     * @JMSSerializer\Groups({"images"})
     */
    protected $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @ODM\Field(type="date")
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("DateTime")
     * @JMSSerializer\Groups({"images"})
     */
    protected $updatedAt;

    /**
     * @ODM\EmbedMany(targetDocument="ImageTag")
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("ArrayCollection<AppBundle\Document\ImageTag>")
     * @JMSSerializer\Groups({"images"})
     */
    protected $tags;

    /**
     * @JMSSerializer\VirtualProperty
     * @JMSSerializer\SerializedName("image_path")
     * @JMSSerializer\Groups({"images"})
     *
     * @return string
     */
    public function getImagePath()
    {
        return self::IMAGE_DIR . $this->imageName;
    }

    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        return [
            'children' => ''
        ];
    }

    /**
     * @ODM\PrePersist
     */
    public function onPrePersist() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ODM\PreFlush
     */
    public function onPreFlush() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        if ($image) {
            $this->imageFile = $image;
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
    
    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * Get imageName
     *
     * @return string $imageName
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add tag
     *
     * @param AppBundle\Document\ImageTag $tag
     */
    public function addTag(\AppBundle\Document\ImageTag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Remove tag
     *
     * @param AppBundle\Document\ImageTag $tag
     */
    public function removeTag(\AppBundle\Document\ImageTag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection $tags
     */
    public function getTags()
    {
        return $this->tags;
    }
}
