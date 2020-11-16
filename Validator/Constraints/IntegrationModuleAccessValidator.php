<?php

namespace RetailCrm\DeliveryModuleBundle\Validator\Constraints;

use RetailCrm\DeliveryModuleBundle\Exception\AbstractModuleException;
use RetailCrm\DeliveryModuleBundle\Exception\ServerUnreachableException;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IntegrationModuleAccessValidator extends ConstraintValidator
{
    /** @var ModuleManagerInterface */
    private $moduleManager;

    public function __construct(ModuleManagerInterface $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    public function validate($account, Constraint $constraint)
    {
        if (!($constraint instanceof IntegrationModuleAccess)) {
            throw new UnexpectedTypeException($constraint, IntegrationModuleAccess::class);
        }

        try {
            $this->moduleManager->checkAccess();
        } catch (ServerUnreachableException $e) {
            $this->context
                ->buildViolation('integration_module_access.server_unreachable_exception')
                ->atPath($constraint->path)
                ->addViolation()
            ;
        } catch (AbstractModuleException $e) {
            $this->context
                ->buildViolation('integration_module_access.module_access_exception')
                ->atPath($constraint->path)
                ->addViolation()
            ;
        }
    }
}
