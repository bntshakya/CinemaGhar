<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    /**
     * The console command description.
     *
     * @var string
     */

    public function __construct()
    {
        if ($this->signature == "make:view {module} {view}") {
            $this->signature = "app:view";
        }

        if ($this->signature == "make:view") {
            $this->signature = "make:view {module} {view}";
        }


        parent::__construct();
    }

    protected $signature = 'make:view {module} {view}';//For Enabling View File
    // protected $signature = 'make:view'; //for Enabling Module View  File
    protected $description = 'Create a view file inside a module\'s resource/views folder';



    public function handle()
    {
        $module = $this->argument('module');
        $view = $this->argument('view');

        $modulePath = base_path("Modules/{$module}/Resources/views");
        $viewPath = "{$modulePath}/{$view}.blade.php";

        if (File::exists($viewPath)) {
            $this->error("View file already exists at {$viewPath}!");
            return;
        }

        if (!File::isDirectory($modulePath)) {
            $this->error("Module directory does not exist: {$modulePath}");
            return;
        }

        File::put($viewPath, '');
        $this->info("View file created successfully at {$viewPath}");
    }


}
