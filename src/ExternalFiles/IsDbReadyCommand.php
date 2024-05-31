<?php
namespace ExternalFiles;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Thanks to Nicolas Chung for this idea :)
 */
#[AsCommand(name: 'app:db:is-ready')]
class IsDbReadyCommand extends Command
{
    private const DEFAULT_CONNECTION_NAME = 'default';
    private const TRIES_NB = 30;

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrineRegistry, string $name = null)
    {
        parent::__construct($name);

        $this->doctrine = $doctrineRegistry;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Wait for database connection to be ready.')
            ->addOption('connection', 'c', InputOption::VALUE_OPTIONAL, 'Specific connection name', self::DEFAULT_CONNECTION_NAME)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connectionName = $input->getOption('connection');

        try {
            $connection = $this->doctrine->getConnection($connectionName);
        } catch (Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');

            return Command::FAILURE;
        }

        $output->writeln('<info>Waiting for database `'.$connectionName.'` to be ready</info>');

        $ready = false;
        $try = 0;

        do {
            try {
                $connection->executeQuery('SHOW TABLES')->fetchAll();

                $ready = true;
            } catch (Exception) {
                $output->write('.');

                sleep(1);
                $try++;
            }
        } while (false === $ready && self::TRIES_NB >= $try);

        $output->writeln('');

        if (self::TRIES_NB < $try) {
            $output->writeln('<error>Database timeout reached</error>');

            return Command::FAILURE;
        }

        $output->writeln('<comment>Database is now ready</comment>');

        return Command::SUCCESS;
    }
}
