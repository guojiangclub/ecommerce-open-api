<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Console;

use ElementVip\Component\User\Models\Role;
use Illuminate\Console\Command;

class RolesCommand extends Command
{
    protected $signature = 'roles:factory';

    protected $description = 'roles factory.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Role::where('name', 'coach')->first()) {
            Role::create(['name' => 'coach', 'display_name' => '教练员角色', 'description' => '教练员角色']);
        }

	    if (!Role::where('name', 'wechatmanager')->first()) {
		    Role::create(['name' => 'wechatmanager', 'display_name' => '微信群管理员', 'description' => '可查看群列表权限']);
	    }
    }
}
