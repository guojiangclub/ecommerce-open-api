<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-03-01
 * Time: 14:04
 */

namespace iBrand\EC\Open\Backend\Store\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmp-store:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install dmp\'s store system.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('dmp-store:default-value');

        $this->call('roles:factory');
    }
}