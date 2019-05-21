<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-8-31 下午8:54
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */

namespace LaravelLicense\Command;

use Collective\Annotations\Console\RouteScanCommand;
use Collective\Annotations\AnnotationsServiceProvider;
use Illuminate\Filesystem\Filesystem;

class RouteRebuild extends RouteScanCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'route:rebuild {--list}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild route from controller annotations';


    public function __construct(Filesystem $files, AnnotationsServiceProvider $provider)
    {
        parent::__construct($files, $provider);
    }

    /**
     * Execute the console command for Laravel 5.5+.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();
        $this->info("Rebuild route complete.");
    }
}
