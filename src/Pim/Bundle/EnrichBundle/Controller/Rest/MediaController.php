<?php

namespace Pim\Bundle\EnrichBundle\Controller\Rest;

use Akeneo\Component\FileStorage\RawFile\RawFileStorerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Media controller
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MediaController
{
    /** @var RawFileStorerInterface */
    protected $storer;

    /**
     * @param RawFileStorerInterface $storer
     */
    public function __construct(RawFileStorerInterface $storer)
    {
        $this->storer = $storer;
    }

    /**
     * Post a new media and return it's temporary identifier
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postAction(Request $request)
    {
        $rawFile = $request->files->get('file');
        $file = $this->storer->store($rawFile, 'storage');

        return new JsonResponse($file->getKey());
    }
}
