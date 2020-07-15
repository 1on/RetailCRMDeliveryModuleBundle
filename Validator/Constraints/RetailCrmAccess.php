<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class RetailCrmAccess extends Constraint
{
    /** @var array */
    public $requiredApiMethods = [];

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
