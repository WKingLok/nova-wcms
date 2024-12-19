<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhotographResource;
use App\Models\Photograph;
use Illuminate\Http\Request;
use Packages\Approval\Enums\ApprovalAction;
use Packages\Approval\Enums\ApprovalStatus;

/**
 * Class PhotographController.
 *
 * @package namespace App\Http\Controllers;
 */
class PhotographController extends Controller
{
    public function photographs(Request $request)
    {
        $list = Photograph::whereHas('approval', function ($query) {
            $query->where('status',  ApprovalStatus::APPROVED)->where('action', ApprovalAction::PUBLISH);
        })->get();

        return response()->json(PhotographResource::collection($list));
    }
}
