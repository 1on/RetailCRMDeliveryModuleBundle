<?php

namespace App\Validator\Constraints;

use RetailCrm\DeliveryModuleBundle\Exception\RetailCrmApiException;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManagerInterface;
use RetailCrm\Exception\CurlException;
use RetailCrm\Exception\InvalidJsonException;
use RetailCrm\Exception\LimitException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RetailCrmAccessValidator extends ConstraintValidator
{
    /** @var ModuleManagerInterface */
    private $moduleManager;

    public function __construct(ModuleManagerInterface $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    public function validate($account, Constraint $constraint)
    {
        if (!($constraint instanceof RetailCrmAccess)) {
            throw new UnexpectedTypeException($constraint, RetailCrmAccess::class);
        }

        $client = $this->moduleManager->getRetailCrmClient();

        try {
            $response = $client->request->credentials();
            if (!$response->isSuccessful()) {
                throw new RetailCrmApiException($response->offsetGet('errorMsg'));
            }

            $credentials = $response->offsetGet('credentials');
            foreach ($constraint->requiredApiMethods as $method) {
                if (!in_array($method, $credentials)) {
                    $this->context
                        ->buildViolation('retailcrm_access.access_denied', ['%method%' => $method])
                        ->atPath('crmApiKey')
                        ->addViolation()
                    ;
                }
            }
        } catch (CurlException $e) {
            $this->context
                ->buildViolation('retailcrm_access.curl_exception')
                ->atPath('crmUrl')
                ->addViolation()
            ;
        } catch (LimitException $e) {
            $this->context
                ->buildViolation('retailcrm_access.service_unavailable')
                ->atPath('crmUrl')
                ->addViolation()
            ;
        } catch (InvalidJsonException $e) {
            $this->context
                ->buildViolation('retailcrm_access.invalid_json')
                ->atPath('crmUrl')
                ->addViolation()
            ;
        } catch (\InvalidArgumentException $e) {
            $this->context
                ->buildViolation('retailcrm_access.requires_https')
                ->atPath('crmUrl')
                ->addViolation()
            ;
        } catch (RetailCrmApiException $e) {
            $this->context
                ->buildViolation('retailcrm_access.wrong_api_key')
                ->atPath('crmUrl')
                ->addViolation()
            ;
        }
    }
}
