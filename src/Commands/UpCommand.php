<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpCommand extends Command
{
    protected static $defaultName = 'up';

    protected function configure()
    {
        $this->setName('up')
            ->setDescription('Bring the application out of maintenance mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $maintenanceFile = __DIR__ . '/../../storage/framework/down';
        if (file_exists($maintenanceFile)) {
            unlink($maintenanceFile);
            $output->writeln("Application is now live.");
        } else {
            $output->writeln("Application is already live.");
        }

        return Command::SUCCESS;
    }
}