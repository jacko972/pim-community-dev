<?php

namespace Pim\Bundle\CatalogBundle\Model;

use Akeneo\Component\FileStorage\Model\FileInterface;

/**
 * Abstract product media (backend type entity)
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class AbstractProductMedia implements ProductMediaInterface
{
    /** @var int|string */
    protected $id;

    /** @var FileInterface */
    protected $file;

//    /** @var string */
//    protected $filename;
//
//    /** @var string */
//    protected $originalFilename;
//
//    /** @var string */
//    protected $mimeType;

    /** @var ProductValueInterface */
    protected $value;

//    /** @var bool */
//    protected $removed = false;
//
//    /** @var int */
//    protected $copyFrom;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function setId($id)
//    {
//        $this->id = $id;
//
//        return $this;
//    }

    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * {@inheritdoc}
     */
    public function setFile(FileInterface $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename()
    {
        if (null === $this->file) {
            return null;
        }

        return $this->file->getKey();
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function setFilename($filename)
//    {
//        $this->filename = $filename;
//
//        return $this;
//    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalFilename()
    {
        if (null === $this->file) {
            return null;
        }

        return $this->file->getOriginalFilename();
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function setOriginalFilename($originalFilename)
//    {
//        $this->originalFilename = $originalFilename;
//
//        return $this;
//    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType()
    {
        if (null === $this->file) {
            return null;
        }

        return $this->file->getMimeType();
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function setMimeType($mimeType)
//    {
//        $this->mimeType = $mimeType;
//
//        return $this;
//    }

//    /**
//     * {@inheritdoc}
//     */
//    public function setRemoved($removed)
//    {
//        $this->removed = $removed;
//
//        return $this;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function isRemoved()
//    {
//        return $this->removed;
//    }

    /**
     * {@inheritdoc}
     */
    public function setValue(ProductValueInterface $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function getCopyFrom()
//    {
//        return $this->copyFrom;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function setCopyFrom($copyFrom)
//    {
//        $this->copyFrom = $copyFrom;
//
//        return $this;
//    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->getFilename();
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function resetFile()
//    {
//        $this->file = null;
//
//        return $this;
//    }
}
