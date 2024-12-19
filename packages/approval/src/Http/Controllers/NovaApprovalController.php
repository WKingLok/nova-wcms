<?php

namespace Packages\Approval\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;
use Laravel\Nova\Http\Resources\DetailViewResource;
use Laravel\Nova\Http\Resources\IndexViewResource;
use Laravel\Nova\Menu\Breadcrumb;
use Laravel\Nova\Menu\Breadcrumbs;
use Laravel\Nova\Nova;
use Laravel\Nova\URL;
use Packages\Approval\Enums\ApprovalAction;

class NovaApprovalController extends Controller
{
    /**
     * Show Resource Update Attached page using Inertia.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Inertia\Response
     */
    public function __invoke(NovaRequest $request)
    {
        $resourceClass = $request->resource();

        $isPolymorphic = function ($query) {
            return is_null($query) || in_array($query, [true, 1, '1']);
        };

        $parentResource = $request->findResourceOrFail();

        return Inertia::render('Approval.Archive', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
            'resourceId' => $request->resourceId,
            'relatedResourceName' => $request->relatedResource,
            'relatedResourceId' => $request->relatedResourceId,
            'viaRelationship' => $request->query('viaRelationship'),
            'viaPivotId' => $request->query('viaPivotId'),
            'polymorphic' => $isPolymorphic($request->query('polymorphic')),
            'viaResource' => $parentResource::uriKey(),
            'viaResourceId' => $parentResource->resource->getKey(),
            'parentResource' => [
                'name' => $parentResource->singularLabel(),
                'display' => $parentResource->title(),
            ],
            'approvalGroupId' => data_get($parentResource->approval->first(), 'group_id')
        ]);
    }


    /**
     * Get breadcrumb menu for the page.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Menu\Breadcrumbs
     */
    protected function breadcrumbs(NovaRequest $request)
    {
        $resourceClass = $request->resource();
        $resource = $request->findResourceOrFail();

        return Breadcrumbs::make(
            collect([Breadcrumb::make(Nova::__('Resources'))])->when($request->viaRelationship(), function ($breadcrumbs) use ($request) {
                return $breadcrumbs->push(
                    Breadcrumb::resource($request->viaResource()),
                    Breadcrumb::resource($request->findParentResource())
                );
            }, function ($breadcrumbs) use ($resourceClass, $resource) {
                return $breadcrumbs->push(
                    Breadcrumb::resource($resourceClass),
                    Breadcrumb::resource($resource),
                );
            })->push(
                Breadcrumb::make(__('Archive'))
            )->all()
        );
    }


    public function actions(Request $request)
    {
        $model =  Nova::modelInstanceForKey($request->resourceName)->find($request->resourceId);
        $action = $request->action;
        $administrator = auth()->user();
        $result = app('approval')->init($model, $administrator)->handleAction($action);

        $redirect = null;

        if (data_get($result, 'action') == ApprovalAction::CLONE) {
            $id = data_get($result, 'newModel.id');
            $redirect = "/resources/$request->resourceName/$id";
        }

        return response()->json([
            'id' => $model->getKey(),
            'redirect' => $redirect,
        ]);
    }

    public function archiveAPI(ResourceIndexRequest $request)
    {
        // $request->viaResourceId
        dd($request->searchIndex());
        return IndexViewResource::make()->toResponse($request);
    }

    public function archiveCountAPI(ResourceIndexRequest $request)
    {
        return response()->json(['count' => $request->toCount()]);
    }
}
