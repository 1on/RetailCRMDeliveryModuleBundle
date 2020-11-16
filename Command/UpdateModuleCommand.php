<?php

namespace RetailCrm\DeliveryModuleBundle\Command;

use RetailCrm\DeliveryModuleBundle\Command\Traits\AccountAwareTrait;
use RetailCrm\DeliveryModuleBundle\Service\AccountManager;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateModuleCommand extends Command
{
    use LockableTrait;
    use AccountAwareTrait;

    /**
     * @var ModuleManagerInterface
     */
    private $moduleManager;

    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('module:update')
            ->setDescription('Update module')
            ->addArgument('accountId', InputArgument::OPTIONAL, 'Choose account, or make it for all');
    }

    public function __construct(ModuleManagerInterface $moduleManager, AccountManager $accountManager)
    {
        $this->moduleManager = $moduleManager;
        $this->accountManager = $accountManager;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        $accountId = $input->hasArgument('accountId')
            ? (int) $input->getArgument('accountId')
            : null;

        $accounts = $this->getAccounts($accountId);

        $count = 0;
        foreach ($accounts as $account) {
            try {
                $this->moduleManager
                    ->setAccount($account)
                    ->updateModuleConfiguration()
                ;
                ++$count;
            } catch (\Exception $e) {
                $output->writeln(
                    "<error>Failed to update configuration for account {$account->getCrmUrl()}[{$account->getId()}]</error>"
                );
                $output->writeln("<error>Error: {$e->getMessage()}</error>");
            }
        }

        $output->writeln("<info>{$count} modules updated.</info>");

        $this->release();

        return 0;
    }
}
