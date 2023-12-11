<?php

namespace Bektasyildiz\LaravelRepository\Commands;

use Dotenv\Util\Str;
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
        if (!File::exists($repositoryFilePath)) {
            File::put($repositoryFilePath, $this->getFileContent($repositoryName, $modelFilePath));
        }
    }

    private function getRepositoryName($modelFilePath)
    {
        return last(explode('/', $modelFilePath)) . 'Repository';
    }

    private function getFileContent(string $repositoryName, string $model): string
    {
        $useModel = str_replace('/', '\\', $model);
        return '<?php
namespace App\Repositories;

use Bektasyildiz\LaravelRepository\Repositories\BaseRepository;
use ' . $useModel . ';

class ' . $repositoryName . ' extends BaseRepository {
    public function __construct(' . last(explode('/', $model)) . ' $model)
    {
        parent::__construct($model);
    }
}';
    }
}
