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
        if (!File::exists($this->getDirectory('public')))
        {
            // Default Asset Theme Folder Create
            $this->setFolder('public');
        }

        if (!File::exists($this->getDirectory('resources',$name)))
        {
            File::makeDirectory($this->getDirectory('resources',$name));
            File::makeDirectory($this->getDirectory('resources',$name.'/views'));

            $this->setFolder('public',$name);


            if ($this->confirm('Create a default theme folder structure?',true)) {
                $this->template($name);
            }
            if ($this->confirm('Create a default asset folder structure?',true)) {
                $this->assets($name);
            }

            if ($this->confirm('Create a webpack helper kit?',true)) {
                $this->webpack($name);
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
            $this->getStub('View','layout')
        );

        $indexTemplate = str_replace(
            [
                '{{layoutName}}'
            ],
            [
                $this->config['views_folder']['layout'].'.'.$this->config['views_blade']['layout'],
            ],
            $this->getStub('View','index')
        );

        $this->setFile('resources',$themeName.'/views/'.$this->config['views_folder']['layout'],$this->config['views_blade']['layout'].'.blade.php',$layoutTemplate);
        $this->setFile('resources',$themeName.'/views/'.$this->config['views_folder']['component'],$this->config['views_blade']['header'].'.blade.php');
        $this->setFile('resources',$themeName.'/views/'.$this->config['views_folder']['component'],$this->config['views_blade']['footer'].'.blade.php');
        $this->setFile('resources',$themeName.'/views/',$this->config['views_blade']['index'].'.blade.php',$indexTemplate);

        $this->info("Theme view files created!");
    }

    protected function assets($themeName)
    {

        $this->setFolder('public',$themeName.'/css');
        $this->setFolder('public',$themeName.'/js');

        $this->setFile('public',$themeName.'/css','app.css');
        $this->setFile('public',$themeName.'/js','app.js');

        $this->info("Theme asset files created!");

    }

    protected function webpack($name)
    {
        $this->setFolder('resources',$name.'/assets');
        $this->setFolder('resources',$name.'/assets/'.$this->config['webpack']['folder']['js']);
        $this->setFolder('resources',$name.'/assets/'.$this->config['webpack']['folder']['css']);

        // Include Js Files
        $this->setFile('resources',$name.'/assets/'.$this->config['webpack']['folder']['js'],$this->config['webpack']['file']['js'],$this->getStub('Webpack','js'));
        $this->setFile('resources',$name.'/assets/'.$this->config['webpack']['folder']['js'],$this->config['webpack']['file']['bootstrap'],$this->getStub('Webpack','bootstrap'));

        // Include Css Files
        $this->setFile('resources',$name.'/assets/'.$this->config['webpack']['folder']['css'],$this->config['webpack']['file']['css'],$this->getStub('Webpack','css'));
        $this->setFile('resources',$name.'/assets/'.$this->config['webpack']['folder']['css'],$this->config['webpack']['file']['variable'],$this->getStub('Webpack','variable'));

        // webpack.mix.js Added
        $include_js = 'resources/'.$this->config['resource_path'].'/'.$name.'/assets/'.$this->config['webpack']['folder']['js'].'/'.$this->config['webpack']['file']['js'];
        $export_js = 'public/'.$this->config['public_path'].'/'.$name.'/js';

        $include_css = 'resources/'.$this->config['resource_path'].'/'.$name.'/assets/'.$this->config['webpack']['folder']['css'].'/'.$this->config['webpack']['file']['css'];
        $export_css = 'public/'.$this->config['public_path'].'/'.$name.'/css';

        File::append(base_path('webpack.mix.js'), "\n" . implode("\n", ["mix.js('".$include_js."', '".$export_js."').sass('".$include_css."', '".$export_css."').version();"]));


    }

    protected function getStub($folder,$fileName)
    {
        return File::get(__DIR__.'/../Template/'.$folder.'/'.$fileName.'.stub');
    }

    protected function getDirectory($folder,$path = NULL)
    {
        if ($folder === 'resources')
        {
            if ($path === NULL)
            {
                return resource_path($this->config['resource_path']);
            }
            return resource_path($this->config['resource_path'].'/'.$path);
        }

        if ($folder === 'public')
        {
            if ($path === NULL)
            {
                return public_path($this->config['public_path']);
            }
            return public_path($this->config['public_path'].'/'.$path);
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
                return File::makeDirectory(resource_path($this->config['resource_path']));
            }
            return File::makeDirectory(resource_path($this->config['resource_path']).'/'.$path);
        }
        if ($directory === 'public')
        {
            if ($path == null)
            {
                return File::makeDirectory(public_path($this->config['resource_path']));
            }
            return File::makeDirectory(public_path($this->config['resource_path']).'/'.$path);
        }
    }
}
