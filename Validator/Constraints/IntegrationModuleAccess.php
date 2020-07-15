<?php

namespace RetailCrm\DeliveryModuleBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class IntegrationModuleAccess extends Constraint
{
    /** @var string */
    public $path = 'login';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
