<?php

namespace App\Validator;

use App\Entity\Library;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MyLibraryValidator extends ConstraintValidator
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint MyLibrary */

        if (!$value instanceof Library) {
            return;
        }

        if ($value->getCreateBy() !== $this->security->getUser()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
