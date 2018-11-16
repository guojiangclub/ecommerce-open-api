<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-01-01
 * Time: 22:53
 */

namespace iBrand\EC\Open\Backend\Store\Listeners;

use iBrand\EC\Open\Backend\Store\Model\Supplier;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLoginListener
{
    public function handle(Login $event)
    {
        $user = $event->user;
        
        if (get_class($user) == config('admin.database.users_model')) {

            /*供应商角色*/
            $current_roles = $user->roles->pluck('slug')->toArray();
            $isSup = false;
            $supID = [];
            if (count($current_roles) == 1 AND str_contains($current_roles[0], 'sup_')) {  //如果是供应商
                if ($supplier = Supplier::where('code', substr($current_roles[0], 4))->where('status', 1)->first()) {
                    $isSup = true;
                    $supID[] = $supplier->id;
                }
            }else{ //如果不是供应商
                $supID[] = 1;
                $supList = array_where($current_roles, function ($value, $key) {
                    return str_contains($value, 'sup_');
                });
                if (count($supList) > 0) {
                    foreach ($supList as $item) {
                        if ($supplier = Supplier::where('code', substr($item, 4))->where('status', 1)->first()) {
                            $supID[] = $supplier->id;
                        }
                    }
                }
            }

            session(['admin_check_supplier' => $isSup, 'admin_supplier_id' => $supID]);
        }
    }
}