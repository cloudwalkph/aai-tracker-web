<?php
namespace App\AAI;

use Countable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;

class AAI implements Countable
{
    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor
     *
     * AAI constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files, Application $app)
    {
        $this->files = $files;
        $this->app   = $app;
        $this->path  = app_path('AAI');
    }

    /**
     * Get all the modules on AAI Folder
     *
     * @return array
     */
    public function all()
    {
        $modules = [];
        $allModules = $this->getAllBaseNames();

        foreach ($allModules as $module) {
            $modules[] = $module;
        }

        return $modules;
    }

    /**
     * Get all the basename of the folders
     *
     * @return array
     */
    public function getAllBaseNames()
    {
        $modules = [];

        $path = $this->path;

        if (! is_dir($path)) {
            return $modules;
        }

        $folders = $this->files->directories($path);

        foreach ($folders as $folder) {
            $modules[] = basename($folder);
        }

        return $modules;
    }

    /**
     * Iterate each module and register them
     *
     * @throws \Exception
     */
    public function register()
    {
        foreach ($this->all() as $module) {
            $this->registerServiceProvider($module);
        }
    }

    /**
     * Register a module into service provider
     *
     * @param $module
     * @throws \Exception
     */
    public function registerServiceProvider($module)
    {
        $file = $this->path . "/Modules/{$module}/Providers/{$module}ServiceProvider.php";
        $namespace = 'App\AAI'."\\Modules\\{$module}\\Providers\\{$module}ServiceProvider";

        if (! $this->files->exists($file)) {
            $message = "Module [{$module}] must have a \"Modules/{$module}/Providers/{$module}ServiceProvider.php\" file";

            throw new \Exception($message);
        }

        $this->app->register($namespace);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        $modules = $this->all();

        return count($modules);
    }
}