<?php

namespace App\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class RequestModel
 *
 * @Annotation
 */
#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RequestModel extends ParamConverter
{
    public function __construct(
        string|array $values,
        array $groups = null,
        array $validationGroups = null,
        array $deserializationGroups = null,
        string $class = null,
        array $options = [],
        bool $isOptional = false,
        string $converter = "fos_rest.request_body"
    ) {
        if (is_string($values)) {
            $values = ['value' => $values];
        }

        $values['class'] = $values['class'] ?? $class;
        $values['options'] = $values['options'] ?? $options;
        $values['isOptional'] = $values['isOptional'] ?? $isOptional;
        $values['converter'] = $values['converter'] ?? $converter;

        $groups = $values['groups'] ?? $groups;
        $validationGroups = $values['validationGroups'] ?? $validationGroups;
        $deserializationGroups = $values['deserializationGroups'] ?? $deserializationGroups;

        if (!isset($values['options']['validator']) && (!is_null($validationGroups) || !is_null($groups))) {
            $values['options']['validator'] = ['groups' => $validationGroups ?? $groups];
        }
        if (!isset($values['options']['deserializationContext']) && (!is_null($deserializationGroups) || !is_null($groups))) {
            $values['options']['deserializationContext'] = ['groups' => $deserializationGroups ?? $groups];
        }

        parent::__construct($values);
    }
}