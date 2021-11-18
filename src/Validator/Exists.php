<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Exists extends Constraint
{
    public string $message = 'The {{ property }} "{{ value }}" is not valid {{ entity }} {{ property }}.';

    public string $property = 'id';

    public ?string $entityClass = null;

    public array $constraints = [];

    public function __construct(array $options = null, string $property = null, string $entityClass = null, string $message = null, array $constraints = null, array $groups = null, $payload = null)
    {
        parent::__construct($options ?? [], $groups, $payload);

        $this->property = $property ?? $this->property;
        $this->entityClass = $entityClass ?? $this->entityClass;
        $this->message = $message ?? $this->message;
        $this->constraints = $constraints ?? $this->constraints;

        if (null === $this->entityClass) {
            throw new MissingOptionsException(
                sprintf('"entityClass" must be given for constraint "%s".', __CLASS__),
                ['entityClass']
            );
        }
    }
}