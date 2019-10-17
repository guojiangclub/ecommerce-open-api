<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Http\Controllers;

use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * @param array $data
     * @param int   $code
     * @param bool  $status
     *
     * @return Response
     */
    public function success($data = [], $code = Response::HTTP_OK, $status = true)
    {
        return new Response(['status' => $status, 'code' => $code, 'data' => empty($data) ? null : $data]);
    }

    /**
     * @param      $message
     * @param int  $code
     * @param bool $status
     *
     * @return mixed
     */
    public function failed($message, $code = Response::HTTP_BAD_REQUEST, $status = false)
    {
        return new Response(['status' => $status, 'code' => $code, 'message' => $message]
        );
    }
}
