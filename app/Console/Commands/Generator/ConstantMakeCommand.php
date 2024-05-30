<?php

namespace App\Console\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:constant')]
class ConstantMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:constant';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:constant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Constant class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Constant';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('code')) {
            return $this->resolveStubPath('/stubs/constant.code.stub');
        }

        return $this->resolveStubPath('/stubs/constant.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services\Constant';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['code', 'c', InputOption::VALUE_NONE, 'Generate a constant helper with code and name string type'],
        ];
    }

}
