<?php
namespace AppBundle\Validator;

use AppBundle\Entity\Board;
use AppBundle\Validator\Constraints\BoardConstraint;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BoardConstraintValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Board $protocol
     * @param Constraint $constraint
     */
    public function validate($protocol, Constraint $constraint)
    {
        /** @var BoardConstraint $constraint */
        if (strlen($protocol->getTitle()) < 5 || strlen($protocol->getContents()) < 10) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', Board::BOARD_VALIDATOR)
                ->addViolation();
        }
    }
}