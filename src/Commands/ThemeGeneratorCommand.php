<?php
namespace Cankod\Theme\Commands;

use Illuminate\Console\Command;
USE Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ThemeGeneratorCommand extends Command {

    protected $signature = 'theme:generate';

    protected $description = 'Generate New Theme';

    protected $config;


    public function __construct()
    {
        $this->config = config('theme');
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->ask('Theme Name');

        $validator = Validator::make([
            'name' => $name,
        ], [
            'name' => ['required'],
        ]);

        if ($validator->fails()) {
            $this->info('Failed to create theme. You must enter the following fields:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return $this->handle();
        }

        if (!File::exists($this->getDirectory('resources')))
        {
            File::makeDirectory($this->getDirectory('resources'));
        }

        if (!File::exists($this->getDirectory('resources',$name)))
        {
            // Themes altına klasör oluştur.
            File::makeDirectory($this->getDirectory('resources',$name));
            File::makeDirectory($this->getDirectory('resources',$name.'/views'));

            if ($this->confirm('Create a default theme folder structure?',true)) {
                $this->template($name);
            }
            if ($this->confirm('Create a default asset folder structure?',true)) {
                $this->assets($name);
            }

            $this->info("Theme Generator Successful. Now add your default theme from the config/theme settings.");
            return;
        }

        $this->error('Theme Folder Previously Created!');

        return $this->handle();

    }

    protected function template($themeName)
    {
        $this->setFolder('resources',$themeName.'/views/'.$this->config['views_folder']['layout']);
        $this->setFolder('resources',$themeName.'/views/'.$this->config['views_folder']['component']);

        $layoutTemplate = str_replace(
            [
                '{{header}}',
                '{{footer}}'
            ],
            [
                $this->config['views_folder']['component'].'.'.$this->config['views_blade']['header'],
                $this->config['views_folder']['component'].'.'.$this->config['views_blade']['footer']
            ],
            $this->getStub('layout')
        );

        $indexTemplate = str_replace(
            [
                '{{layoutName}}'
            ],
            [
                $this->config['views_folder']['layout'].'.'.$this->config['views_blade']['layout'],
            ],
            $this->getStub('index')
        );

        $this->setFile('resources',$themeName.'/views/'.$this->config['views_folder']['layout'],$this->config['views_blade']['layout'].'.blade.php',$layoutTemplate);
        $this->setFile('resources',$themeName.'/views/'.$this->config['views_folder']['component'],$this->config['views_blade']['header'].'.blade.php');
        $this->setFile('resources',$themeName.'/views/'.$this->config['views_folder']['component'],$this->config['views_blade']['footer'].'.blade.php');
        $this->setFile('resources',$themeName.'/views/',$this->config['views_blade']['index'].'.blade.php',$indexTemplate);

        $this->info("Theme view files created!");
    }

    protected function assets($themeName)
    {

        if (!File::exists($this->getDirectory('public')))
        {
            // Default Asset Theme Folder Create
            $this->setFolder('public');
        }

        if (!File::exists($this->getDirectory('public',$themeName)))
        {
            $this->setFolder('public',$themeName);
            $this->setFolder('public',$themeName.'/css');
            $this->setFolder('public',$themeName.'/js');

            $this->setFile('public',$themeName.'/css','app.css');
            $this->setFile('public',$themeName.'/js','app.js');

            $this->info("Theme asset files created!");

            return;
        }

    }

    protected function getStub($fileName)
    {
        return File::get(__DIR__.'/../Template/'.$fileName.'.stub');
    }

    protected function getDirectory($folder,$path = NULL)
    {
        if ($folder === 'resources')
        {
            if ($path === NULL)
            {
                return resource_path($this->config['theme_path']);
            }
            return resource_path($this->config['theme_path'].'/'.$path);
        }

        if ($folder === 'public')
        {
            if ($path === NULL)
            {
                return public_path($this->config['asset_path']);
            }
            return public_path($this->config['asset_path'].'/'.$path);
        }

    }

    protected function setFile($directory,$folder,$file,$content = NULL)
    {
        if ($directory === 'resources')
        {
            return File::put($this->getDirectory('resources',$folder).'/'.$file,$content);
        }
        if ($directory === 'public')
        {
            return File::put($this->getDirectory('public',$folder).'/'.$file,$content);
        }
    }

    protected function setFolder($directory,$path = null)
    {
        if ($directory === 'resources')
        {
            if ($path == null)
            {
                return File::makeDirectory(resource_path($this->config['theme_path']));
            }
            return File::makeDirectory(resource_path($this->config['theme_path']).'/'.$path);
        }
        if ($directory === 'public')
        {
            if ($path == null)
            {
                return File::makeDirectory(public_path($this->config['theme_path']));
            }
            return File::makeDirectory(public_path($this->config['theme_path']).'/'.$path);
        }
    }
}
