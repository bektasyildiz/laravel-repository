<?php

namespace Bektasyildiz\LaravelRepository\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {modelFilePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genereta new respository by model file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelFilePath = $this->argument('modelFilePath');
        $repositoryName = $this->getRepositoryName($modelFilePath);
        $repositoryFilePath = config('laravel-respository.directory') . '/' . $repositoryName . '.php';
        if (File::exists($repositoryFilePath)) {
            $this->info('Repository file already exists!');
        }
        File::put($repositoryFilePath, $this->getFileContent($repositoryName, $modelFilePath));
    }

    private function getRepositoryName($modelFilePath)
    {
        return last(explode('/', $modelFilePath)) . 'Repository';
    }

    private function getFileContent(string $repositoryName, string $model): string
    {
        $fileContent = File::get(__DIR__ . '/../../templates/repository.template');
        $useModel = str_replace('/', '\\', $model);
        $namespace = $this->directoryToNamespace(config('laravel-respository.directory'));
        $search = ['{{ useModel }}', '{{ namespace }}', '{{ class }}'];
        $replace = [$useModel, $namespace, $repositoryName];
        return str_replace($search, $replace, $fileContent);
    }

    private function directoryToNamespace($dir)
    {
        $deleteLastSlash = preg_replace('/\/$/', '', $dir);
        return ucfirst(str_replace('/', '\\', $deleteLastSlash));
    }
}
