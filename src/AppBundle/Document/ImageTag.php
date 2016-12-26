<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class ImageTag
{
    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Assert\NotBlank(message="You need to enter tag name")
     * @var string $tagName
     */
    protected $tagName;

    /** @ODM\EmbedOne(targetDocument="Image") */
    protected $image;

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
     * Set tagName
     *
     * @param string $tagName
     * @return $this
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
        return $this;
    }

    /**
     * Get tagName
     *
     * @return string $tagName
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Set image
     *
     * @param AppBundle\Document\Image $image
     * @return $this
     */
    public function setImage(\AppBundle\Document\Image $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return AppBundle\Document\Image $image
     */
    public function getImage()
    {
        return $this->image;
    }
}
