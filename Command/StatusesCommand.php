<?php

namespace RetailCrm\DeliveryModuleBundle\Command;

use Doctrine\Persistence\ObjectManager;
use RetailCrm\DeliveryModuleBundle\Service\AccountManager;
use RetailCrm\DeliveryModuleBundle\Service\ModuleManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusesCommand extends Command
{
    use LockableTrait;

    const QUERY_MAX_RESULTS = 100;

    /**
     * @var ModuleManagerInterface
     */
    private $moduleManager;

    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('statuses:update')
            ->setDescription('Update statuses')
            ->addArgument('accountId', InputArgument::OPTIONAL, 'Choose account, or make it for all')
        ;
    }

    public function __construct(ModuleManagerInterface $moduleManager, AccountManager $accountManager, ObjectManager $entityManager)
    {
        $this->moduleManager = $moduleManager;
        $this->accountManager = $accountManager;
        $this->entityManager = $entityManager;

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

        $accountQueryBuilder = $this->accountManager->getActiveQueryBuilder()
            ->andWhere('account.id > :lastId')
            ->setMaxResults(static::QUERY_MAX_RESULTS)
        ;

        if (null !== $accountId) {
            $accountQueryBuilder
                ->andWhere('account.id = :accountId')
                ->setParameter('accountId', $accountId)
            ;
        }

        $accountQuery = $accountQueryBuilder->getQuery();

        $commandResult = 0;
        $count = 0;
        $lastId = 0;
        while (true) {
            $accountQuery->setParameter('lastId', $lastId);

            $result = $accountQuery->getResult();
            if (empty($result)) {
                break;
            }

            foreach ($result as $account) {
                $lastId = $account->getId();

                try {
                    $count += $this->moduleManager
                        ->setAccount($account)
                        ->updateStatuses()
                    ;
                } catch (\Exception $e) {
                    $output->writeln(
                        "<error>Failed to update statuses for account {$account->getCrmUrl()}[{$account->getId()}]</error>"
                    );
                    $output->writeln("<error>Error: {$e->getMessage()}</error>");

                    $commandResult = 1;
                }
            }

            $this->entityManager->clear();
            gc_collect_cycles();
        }

        $output->writeln("<info>{$count} statuses updated.</info>");

        $this->release();

        return $commandResult;
    }
}
