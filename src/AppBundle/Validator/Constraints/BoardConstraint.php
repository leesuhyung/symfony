<?php
namespace AppBundle\Validator\Constraints;

use AppBundle\Validator\BoardConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BoardConstraint extends Constraint
{
    public $message = '{{ string }}은 최소 5자 이상 이여야 합니다.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return BoardConstraintValidator::class;
    }

}