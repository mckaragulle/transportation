<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeFullPattern extends Command
{
    protected $signature = 'make:full-pattern {name} {location}';
    protected $description = 'Create Repository, Service, and Observer for a given name';

    public function handle()
    {
        $name = $this->argument('name');
        $location = $this->argument('location');
        $filesystem = new Filesystem();
        $model = "App\\Models\\{$location}\\{$name}";

        // Repository oluştur
        $this->generateStub('repository', "app/Repositories/{$location}/{$name}Repository.php", $name, $model);

        // Service oluştur
        $this->generateStub('service', "app/Services/{$location}/{$name}Service.php", $name, $model, $location);

        // Observer oluştur
        $this->generateStub('observer', "app/Observers/{$location}/{$name}Observer.php", $name, $model);

        $this->info("Repository, Service, and Observer created for {$location}/{$name}. {$model}");
    }

    protected function generateStub($stubType, $path, $name, $modelNamespace = null, $location = null)
    {
        $stub = file_get_contents(base_path("stubs/{$stubType}.stub"));

        $stub = str_replace('{{ namespace }}', $this->getNamespace($path), $stub);
        $stub = str_replace('{{ class }}', $name . ucfirst($stubType), $stub);

        if ($modelNamespace) {
            $stub = str_replace('{{ modelNamespace }}', $modelNamespace, $stub);
            $stub = str_replace('{{ model }}', $name, $stub);
        }
        if ($location) {
            $stub = str_replace('{{ location }}', $location, $stub);
        }

        file_put_contents(base_path($path), $stub);
    }

    protected function getNamespace($path)
    {
        return 'App\\' . str_replace(['app/', '/', '.php'], ['', '\\', ''], dirname($path));
    }
}
