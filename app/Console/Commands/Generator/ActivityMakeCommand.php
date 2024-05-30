<?php

namespace App\Console\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:activity')]
class ActivityMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:activity';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Activity class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Activity';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/activity.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Models';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));
        $names = preg_split('~[\\\\/]~', $name);

        $class = last($names);
        $class = 'HasActivity' . $class . 'Property';

        $names[array_key_last($names)] = 'Traits';
        $names[] = $class;

        return implode('\\', $names);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel', 'ActiveVersion'],
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ namespacedUserModel }}', '{{ activeVersion }}'],
            ['{{namespace}}', '{{rootNamespace}}', '{{namespacedUserModel}}', '{{activeVersion}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel(), $this->getActiveVersion()],
                $stub
            );
        }

        return $this;
    }

    /**
     * Get the active version in this app.
     *
     * @return string
     */
    private function getActiveVersion()
    {
        return config('base.conf.version');
    }

}
