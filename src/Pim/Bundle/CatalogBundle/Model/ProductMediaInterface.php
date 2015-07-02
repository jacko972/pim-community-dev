<?php

namespace Pim\Bundle\CatalogBundle\Model;

use Akeneo\Component\FileStorage\Model\FileInterface;

/**
 * Product media interface (backend type entity)
 *
 * @author    Julien Janvier <julien.janvier@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ProductMediaInterface
{
    /**
     * Get the product value
     *
     * @return ProductValueInterface
     */
    public function getValue();

//    /**
//     * TODO: remove it
//     *
//     * Set id
//     *
//     * @param int|string $id
//     *
//     * @return ProductMediaInterface
//     */
//    public function setId($id);

    /**
     * Set file
     *
     * @param FileInterface $file
     *
     * @return ProductMediaInterface
     */
    public function setFile(FileInterface $file);

//    /**
//     * TODO: remove it
//     *
//     * @return bool
//     */
//    public function isRemoved();

    /**
     * Set the product value
     *
     * @param ProductValueInterface $value
     *
     * @return ProductMediaInterface
     */
    public function setValue(ProductValueInterface $value);

//    /**
//     * TODO: remove it
//     *
//     * Set mime type
//     *
//     * @param string $mimeType
//     *
//     * @return ProductMediaInterface
//     */
//    public function setMimeType($mimeType);

//    /**
//     * TODO: remove it
//     *
//     * Reset the media file
//     *
//     * @return ProductMediaInterface
//     */
//    public function resetFile();

//    /**
//     * TODO: remove it
//     *
//     * Set the media id to copy from
//     *
//     * @param int $copyFrom
//     *
//     * @return ProductMediaInterface
//     */
//    public function setCopyFrom($copyFrom);

//    /**
//     * TODO: remove it
//     *
//     * Set original filename
//     *
//     * @param string $originalFilename
//     *
//     * @return ProductMediaInterface
//     */
//    public function setOriginalFilename($originalFilename);

    /**
     * TODO: remove it or shortcut it
     *
     * Get original filename
     *
     * @return string
     */
    public function getOriginalFilename();

    /**
     * TODO: remove it or shortcut it
     *
     * Get mime type
     *
     * @return string
     */
    public function getMimeType();

//    /**
//     * TODO: remove it
//     *
//     * Set filename
//     *
//     * @param string $filename
//     *
//     * @return ProductMediaInterface
//     */
//    public function setFilename($filename);

    /**
     * To string
     *
     * @return string
     */
    public function __toString();

    /**
     * Get id
     *
     * @return int|string
     */
    public function getId();

    /**
     * TODO: remove it or shortcut it
     *
     * Get filename
     *
     * @return string
     */
    public function getFilename();

//    /**
//     * TODO: remove it
//     *
//     * Get the media id to copy from
//     *
//     * @return int
//     */
//    public function getCopyFrom();

    /**
     * Get file
     *
     * @return FileInterface
     */
    public function getFile();

//    /**
//     * TODO: remove it
//     *
//     * @param bool $removed
//     *
//     * @return ProductMediaInterface
//     */
//    public function setRemoved($removed);
}
