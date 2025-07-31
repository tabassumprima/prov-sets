<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\{Pluralizer, Str};

class ModuleMakeCommand extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create basic files for specific module';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

     /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $class;

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $namespace;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath()
    {
        return base_path('stubs/controller.custom.stub');
    }

    public function getNamespace()
    {
        $this->namespace = Str::before($this->getSingularClassName($this->argument('name')), '/'.class_basename($this->argument('name')));
    }

    public function getClassName()
    {
        $this->class = class_basename($this->argument('name'));
    }

    /**
    **
    * Map the stub variables present in stub to its value
    *
    * @return array
    *
    */
    public function getStubVariables()
    {
        return [
            'NAMESPACE'             => 'App\\Http\\Controllers\\' . $this->namespace,
            'CLASS_NAME'            => $this->getSingularClassName($this->class),
            'VARIABLE_NAME'         => Str::camel($this->argument('name')),
            'VIEW_NAME'             => Str::snake($this->class),
            'ROUTER_NAME'           => Str::snake($this->class, '-'),
            'VIEW_FOLDER'           => Str::lower($this->getSingularClassName($this->namespace)) ?? null,
            'SERVICE_NAME'          => Str::camel($this->class) .'Service',
            'VARIABLE_NAME_PLURAL'  => Str::camel($this->getPluralClassName($this->class)),
            'TRANSLATION_FOLDER'    => Str::lower($this->getSingularClassName($this->namespace)) ?? null,
            'TRANSLATION'           => Str::lower(Str::snake($this->getSingularClassName($this->class))),

        ];
    }

    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFile()
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('$'.$search.'$' , $replace, $contents);
        }

        return $contents;

    }

    public function modelExist($model)
    {
        // $controller = class_basename($action['controller']);
        $model_name = 'App\\Models\\' . $model. 'Model';
        $this->info($model_name);
        if(class_exists($model_name))
            return true;
        else
           return false;
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        return 'app/Http/Controllers' .'/' .$this->getSingularClassName($this->argument('name')) . 'Controller.php';
    }

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
     */
    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function getPluralClassName($name)
    {
        return ucwords(Pluralizer::plural($name));
    }

    public function handle()
    {
        $this->getNamespace();
        $this->getClassName();
        $this->createController();
        $this->info("Controller created");
        $this->createModel();
        $this->info("Model created");
        $this->createRequest();
        $this->info("Request created");
        $this->createMigration();
        $this->info("Migration created");
        $this->createService();
        $this->info("Service created");
        $this->createTranslarion();
        $this->info("Translation created");

    }

    protected function createController()
    {
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("Service : {$path} created");
        } else {
            $this->info("Service : {$path}  already exists");
        }
    }



     /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));


        $this->call('make:migration', [
            'name' => "create_{$table}_table"
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createRequest()
    {
        $request = Str::studly(class_basename($this->argument('name')));


        $this->call('make:request', [
            'name' => $request . "/Request",
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createService()
    {
        $table = Str::studly(class_basename($this->argument('name')));
        $this->info($table);

        $this->call('make:service', [
            'name' => $table
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createModel()
    {
        $model = Str::studly(class_basename($this->argument('name')));


        $this->call('make:model', [
            'name' => "{$model}"
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createTranslarion()
    {
        // $model = Str::studly(class_basename($this->argument('name')));


        $this->call('make:translation', [
            'name' => "{$this->argument('name')}",
        ]);
    }
}
