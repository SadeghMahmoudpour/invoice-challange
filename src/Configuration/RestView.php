<?php

namespace App\Configuration;
use FOS\RestBundle\Controller\Annotations\View;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class RestView extends View
{
    public function __construct(
        $data = [],
        array $vars = [],
        bool $isStreamable = false,
        array $owner = [],
        int $statusCode = null,
        array $serializerGroups = [],
        bool $serializerEnableMaxDepthChecks = null
    ) {
        parent::__construct($data, $vars, $isStreamable, $owner);

        if (is_array($data)) {
            $statusCode = $data['statusCode'] ?? $statusCode;
            $serializerGroups = $data['serializerGroups'] ?? $serializerGroups;
            $serializerEnableMaxDepthChecks = $data['serializerEnableMaxDepthChecks'] ?? $serializerEnableMaxDepthChecks;
        }

        $this->setStatusCode($statusCode);
        $this->setSerializerGroups($serializerGroups);
        $this->setSerializerEnableMaxDepthChecks($serializerEnableMaxDepthChecks);
    }
}