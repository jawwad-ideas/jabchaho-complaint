<?php

namespace App\Traits\QueueWorker;

trait QueueWorkerTrait
{
     //Function for queue worker start and termination
    protected function queueWorker()
    { 
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
        {
            $artisanPath = str_replace('\\', '/', realpath(base_path('artisan')));
            $command = "start /B php ".$artisanPath." queue:work --stop-when-empty";
            pclose(popen($command, "r"));
        } 
        else 
        {
            exec('php ' . base_path('artisan') . ' queue:work --stop-when-empty --daemon --timeout=60 > /dev/null 2>&1 &');
        }
        
    }
}