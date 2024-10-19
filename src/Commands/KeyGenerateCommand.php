<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class KeyGenerateCommand extends Command
{
    protected static $defaultName = 'key:generate';

    protected function configure()
    {
        $this->setName('key:generate')
            ->setDescription('Generate a new application key');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = bin2hex(random_bytes(32));
        $envFile = __DIR__ . '/../../.env';
        $envContent = file_get_contents($envFile);
        $envContent = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $key, $envContent);
        file_put_contents($envFile, $envContent);

        $output->writeln("Application key [{$key}] set successfully.");

        return Command::SUCCESS;
    }
}