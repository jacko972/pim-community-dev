<?php

namespace Pim\Bundle\CatalogBundle\Validator\Constraints;

use Akeneo\Component\FileStorage\Model\FileInterface;
use Pim\Bundle\CatalogBundle\Model\ProductMediaInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator as BaseFileValidator;

/**
 * Constraint.
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class FileValidator extends BaseFileValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof ProductMediaInterface) {
            $value = $value->getFile();
        }

        if (null === $value || '' === $value) {
            return;
        }

//        TODO: handle file validation
//        parent::validate($value, $constraint);

        $this->validateAllowedExtension($value, $constraint);
    }

    /**
     * Validate if extension is allowed.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    protected function validateAllowedExtension($value, Constraint $constraint)
    {
        if ($constraint->allowedExtensions) {

            if ($value instanceof FileInterface) {
                $extension = $value->getExtension();
            } elseif ($value instanceof UploadedFile) {
                $extension = $value->getClientOriginalExtension();
            } elseif ($value instanceof \SplFileInfo) {
                $extension = $value->getExtension();
            } else {
                $file = new \SplFileInfo($value);
                $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            }

            if (!in_array(strtolower($extension), $constraint->allowedExtensions)) {
                $this->context->addViolation(
                    $constraint->extensionsMessage,
                    ['%extensions%' => implode(', ', $constraint->allowedExtensions)]
                );
            }
        }
    }
}
