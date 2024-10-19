<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownCommand extends Command
{
    protected static $defaultName = 'down';

    protected function configure()
    {
        $this->setName('down')
            ->setDescription('Put the application into maintenance mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $maintenanceFile = __DIR__ . '/../../storage/framework/down';
        file_put_contents($maintenanceFile, 'The application is now in maintenance mode.');

        $output->writeln("Application is now in maintenance mode.");

        return Command::SUCCESS;
    }
}