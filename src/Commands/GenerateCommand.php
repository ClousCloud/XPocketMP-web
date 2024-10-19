<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    protected static $defaultName = 'make:generate';

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Generate a new middleware, view, model, or controller.')
            ->addArgument('type', InputArgument::REQUIRED, 'The type of file to generate (middleware, view, model, controller).')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the file to generate.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type');
        $name = $input->getArgument('name');
        $content = '';
        $directory = '';

        switch ($type) {
            case 'middleware':
                $directory = 'src/middleware';
                $content = $this->getMiddlewareTemplate($name);
                break;
            case 'view':
                $directory = 'src/views';
                $content = $this->getViewTemplate($name);
                break;
            case 'model':
                $directory = 'src/models';
                $content = $this->getModelTemplate($name);
                break;
            case 'controller':
                $directory = 'src/controllers';
                $content = $this->getControllerTemplate($name);
                break;
            default:
                $output->writeln('Invalid type specified.');
                return Command::FAILURE;
        }

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filePath = $directory . '/' . $name . '.php';
        if (file_put_contents($filePath, $content) !== false) {
            $output->writeln("Successfully created $type: $name");
            return Command::SUCCESS;
        } else {
            $output->writeln("Failed to create $type: $name");
            return Command::FAILURE;
        }
    }

    private function getMiddlewareTemplate($name): string
    {
        return <<<EOD
<?php

namespace App\Middleware;

class $name
{
    public function handle(\$request, \$next)
    {
        // Middleware logic here
        return \$next(\$request);
    }
}
EOD;
    }

    private function getViewTemplate($name): string
    {
        return <<<EOD
<!-- $name View -->
<h1>$name</h1>
<p>Content for $name view.</p>
EOD;
    }

    private function getModelTemplate($name): string
    {
        return <<<EOD
<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class $name
{
    // Model logic here
}
EOD;
    }

    private function getControllerTemplate($name): string
    {
        return <<<EOD
<?php

namespace App\Controllers;

class $name
{
    public function index()
    {
        // Controller logic here
    }
}
EOD;
    }
}