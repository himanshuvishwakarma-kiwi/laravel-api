<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *    version="1.0.0",
 *    title="Laravel api with jwt Auth Documentation",
 *    description="Laravel api description",
 *    @OA\Contact(
 *       email="admin@admin.com"
 *    )
 *  )
 *
**/

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
