<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository and its interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modelName = ucfirst($name);
        $interfaceName = "{$modelName}RepositoryInterface";
        $repositoryName = "{$modelName}Repository";

        $repositoriesPath = app_path('Repositories');
        $interfacesPath = app_path('Repositories/Interfaces');

        if (!is_dir($repositoriesPath)) {
            mkdir($repositoriesPath, 0755, true);
        }

        if (!is_dir($interfacesPath)) {
            mkdir($interfacesPath, 0755, true);
        }

        $this->createRepositoryInterface($interfacesPath, $interfaceName);
        $this->createRepository($repositoriesPath, $modelName, $repositoryName, $interfaceName);
        $this->info('Repository and Interface created successfully.');
    }

    protected function createRepositoryInterface($interfacesPath, $interfaceName)
    {
        $interfacePath = "{$interfacesPath}/{$interfaceName}.php";

        if (!file_exists($interfacePath)) {
            $template = file_get_contents(app_path('Templates/repository_interface.stub'));
            $template = str_replace('{{ interfaceName }}', $interfaceName, $template);
            file_put_contents($interfacePath, $template);
            $this->info("Interface {$interfaceName} created.");
        } else {
            $this->warn("Interface {$interfaceName} already exists.");
        }
    }

    protected function createRepository($repositoriesPath, $modelName, $repositoryName, $interfaceName)
    {
        $repositoryPath = "{$repositoriesPath}/{$repositoryName}.php";

        if (!file_exists($repositoryPath)) {
            $template = file_get_contents(app_path('Templates/repository.stub'));
            $template = str_replace('{{ modelName }}', $modelName, $template);
            $template = str_replace('{{ repositoryName }}', $repositoryName, $template);
            $template = str_replace('{{ interfaceName }}', $interfaceName, $template);
            file_put_contents($repositoryPath, $template);
            $this->info("Repository {$repositoryName} created.");
        } else {
            $this->warn("Repository {$repositoryName} already exists.");
        }
    }

}
