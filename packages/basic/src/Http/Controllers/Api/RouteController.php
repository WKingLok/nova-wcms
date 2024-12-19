<?php

namespace Packages\Basic\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Tighten\Ziggy\Ziggy;

/**
 * Class PageController.
 *
 * @package namespace App\Http\Controllers\Wcms;
 */
class RouteController extends Controller
{
    public function ziggy()
    {
        return response()->json(new Ziggy);
    }
}
