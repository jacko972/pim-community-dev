<?php

namespace Pim\Bundle\CatalogBundle\Factory;

use Akeneo\Component\FileStorage\Model\FileInterface;

/**
 * A Media object factory
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MediaFactory
{
    /** @var string */
    protected $mediaClass;

    /**
     * @param string $mediaClass
     */
    public function __construct($mediaClass)
    {
        $this->mediaClass = $mediaClass;
    }

    /**
     * @param FileInterface|null $file
     *
     * @return \Pim\Bundle\CatalogBundle\Model\ProductMediaInterface
     */
    public function createMedia(FileInterface $file = null)
    {
        $media = new $this->mediaClass();
        if (null !== $file) {
            $media->setFile($file);
        }

        return $media;
    }
}
