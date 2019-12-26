<?php

namespace RetailCrm\DeliveryModuleBundle;

use Doctrine\ORM\Tools\Pagination\Paginator;
use RetailCrm\DeliveryModuleBundle\Entity\Account;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateModuleConfigurationCommand extends ContainerAwareCommand
{
    use LockableTrait;

    private $em;
    private $manager;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('configuration:update')
            ->setDescription('Update module configuration')
            ->addArgument('accountId', InputArgument::OPTIONAL, 'Choose account, or make it for all');
    }

    public function __construct(EntityManagerInterface $em, ModuleManager $manager)
    {
        $this->em = $em;
        $this->manager = $manager;

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

        $accountId = $input->getArgument('accountId')
            ? (int) $input->getArgument('accountId')
            : null;

        $paginator = [];
        if (null !== $accountId) {
            $paginator = [$this->em->getRepository($this->getConnectionClass())->find($accountId)];
        } else {
            $accountQuery = $this->em->createQuery('
                SELECT account
                FROM ' . Account::class . ' account
                WHERE
                    account.isActive = true
                    AND account.isFreeze != true
            ')
               ->setFirstResult(0)
               ->setMaxResults(100);
            $paginator = new Paginator($accountQuery);
        }

        $count = 0;
        foreach ($paginator as $account) {
            try {
                $this->manager
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

        $output->writeln("<info> {$count} modules updated.</info>");

        $this->release();
    }
}
