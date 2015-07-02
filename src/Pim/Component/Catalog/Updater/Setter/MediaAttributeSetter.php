<?php

namespace Pim\Component\Catalog\Updater\Setter;

use Akeneo\Component\FileStorage\Model\FileInterface;
use Akeneo\Component\FileStorage\Repository\FileRepositoryInterface;
use Pim\Bundle\CatalogBundle\Builder\ProductBuilderInterface;
use Pim\Bundle\CatalogBundle\Exception\InvalidArgumentException;
use Pim\Bundle\CatalogBundle\Factory\MediaFactory;
use Pim\Bundle\CatalogBundle\Model\AttributeInterface;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;
use Pim\Bundle\CatalogBundle\Validator\AttributeValidatorHelper;

/**
 * Sets a media value in many products
 *
 * @author    Julien Janvier <julien.janvier@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MediaAttributeSetter extends AbstractAttributeSetter
{
    /** @var MediaFactory */
    protected $mediaFactory;

    /** @var FileRepositoryInterface */
    protected $fileRepository;

    /**
     * @param ProductBuilderInterface  $productBuilder
     * @param AttributeValidatorHelper $attrValidatorHelper
     * @param FileRepositoryInterface  $fileRepository
     * @param MediaFactory             $mediaFactory
     * @param array                    $supportedTypes
     */
    public function __construct(
        ProductBuilderInterface $productBuilder,
        AttributeValidatorHelper $attrValidatorHelper,
        FileRepositoryInterface $fileRepository,
        MediaFactory $mediaFactory,
        array $supportedTypes
    ) {
        parent::__construct($productBuilder, $attrValidatorHelper);

        $this->fileRepository = $fileRepository;
        $this->mediaFactory   = $mediaFactory;
        $this->supportedTypes = $supportedTypes;
    }

    /**
     * {@inheritdoc}
     *
     * Expected data input format :
     * {
     *     "originalFilename": "original_filename.extension",
     *     "filePath": "/current/file/path/original_filename.extension"
     * }
     */
    public function setAttributeData(
        ProductInterface $product,
        AttributeInterface $attribute,
        $data,
        array $options = []
    ) {
        $options = $this->resolver->resolve($options);
        $this->checkLocaleAndScope($attribute, $options['locale'], $options['scope'], 'media');
        $this->checkData($attribute, $data);

        if (null === $data) {
            $file = null;
        } else {
            $file = $this->fileRepository->findOneByIdentifier($data);
            if (null === $file) {
                throw InvalidArgumentException::validEntityCodeExpected(
                    $attribute->getCode(),
                    'key',
                    'The file does not exist',
                    'setter',
                    'media',
                    $data
                );
            }
        }

        $this->setMedia($product, $attribute, $file, $options['locale'], $options['scope']);
    }

    /**
     * Set media in the product value
     *
     * @param ProductInterface   $product
     * @param AttributeInterface $attribute
     * @param FileInterface|null $file
     * @param string|null        $locale
     * @param string|null        $scope
     */
    private function setMedia(
        ProductInterface $product,
        AttributeInterface $attribute,
        FileInterface $file = null,
        $locale = null,
        $scope = null
    ) {
        $value = $product->getValue($attribute->getCode(), $locale, $scope);
        if (null === $value) {
            $value = $this->productBuilder->addProductValue($product, $attribute, $locale, $scope);
        }

        if (null === $media = $value->getMedia()) {
            $media = $this->mediaFactory->createMedia($file);
        } else {
            $media->setFile($file);
        }

        $value->setMedia($media);
    }

    /**
     * @param AttributeInterface $attribute
     * @param mixed              $data
     */
    private function checkData(AttributeInterface $attribute, $data)
    {
        if (null === $data) {
            return;
        }

        if (!is_string($data)) {
            throw InvalidArgumentException::stringExpected(
                $attribute->getCode(),
                'setter',
                'media',
                gettype($data)
            );
        }
    }
}
