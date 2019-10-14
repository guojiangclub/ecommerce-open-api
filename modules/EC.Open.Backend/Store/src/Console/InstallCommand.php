<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-03-01
 * Time: 14:04
 */

namespace GuoJiangClub\EC\Open\Backend\Store\Console;

use GuoJiangClub\EC\Open\Backend\Member\Seeds\MemberBackendTablesSeeder;
use GuoJiangClub\EC\Open\Backend\Store\Seeds\StoreBackendTablesSeeder;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibrand:store-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install ibrand\'s store backend system.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('ibrand:backend-install');
        $this->call('ibrand:store-default-value');
        $this->call('ibrand:store-default-specs');
        $this->call('db:seed', ['--class' => StoreBackendTablesSeeder::class]);
        $this->call('db:seed', ['--class' => MemberBackendTablesSeeder::class]);
    }
}