<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        if (!$constraint instanceof Exists) {
            throw new LogicException(
                \sprintf('You can only pass %s constraint to this validator.', Exists::class)
            );
        }

        if (empty($constraint->entityClass)) {
            throw new LogicException(\sprintf('Must set "entityClass" on "%s" validator', Exists::class));
        }

        $queryConstraint = $constraint->constraints;
        $queryConstraint[$constraint->property] = $value;

        if (is_scalar($value)) {
            $data = $this->entityManager->getRepository($constraint->entityClass)->findOneBy($queryConstraint);

            if (null === $data) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ entity }}', $constraint->entityClass)
                    ->setParameter('{{ value }}', $value)
                    ->setParameter('{{ property }}', $constraint->property)
                    ->addViolation();
            }
        }

        if (is_array($value)) {
            $data = $this->entityManager->getRepository($constraint->entityClass)->findBy($queryConstraint);

            if (count($data) !== count($value)) {
                $this->context->buildViolation("One or more of the given values is invalid.")
                    ->addViolation();
            }
        }
    }
}