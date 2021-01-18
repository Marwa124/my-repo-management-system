<?php

namespace Modules\ProjectManagement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\ProjectManagement\Http\Requests\MassDestroyWorkTrackingRequest;
use Modules\ProjectManagement\Http\Requests\StoreWorkTrackingRequest;
use Modules\ProjectManagement\Http\Requests\UpdateWorkTrackingRequest;
use Modules\HR\Entities\AccountDetail;
//use App\Models\Permission;
use Modules\ProjectManagement\Entities\TimeWorkType;
use Modules\ProjectManagement\Entities\WorkTracking;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkTrackingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('work_tracking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (request()->segment(count(request()->segments())) == 'trashed'){

            abort_if(Gate::denies('work_tracking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $trashed = true;

            $workTrackings = WorkTracking::onlyTrashed()->get();

            return view('projectmanagement::admin.workTrackings.index', compact('workTrackings','trashed'));
        }


        $trashed = false;

        $workTrackings = WorkTracking::all();

        return view('projectmanagement::admin.workTrackings.index', compact('workTrackings','trashed'));
    }

    public function create()
    {
        abort_if(Gate::denies('work_tracking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $work_types = TimeWorkType::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('projectmanagement::admin.workTrackings.create', compact('work_types'));
    }

    public function store(StoreWorkTrackingRequest $request)
    {
        $workTracking = WorkTracking::create($request->all());

        return redirect()->route('projectmanagement.admin.work-trackings.index');
    }

    public function edit(WorkTracking $workTracking)
    {
        abort_if(Gate::denies('work_tracking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $work_types = TimeWorkType::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        //$permissions = Permission::all()->pluck('title', 'id');

        //$accounts = Account::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $workTracking->load('work_type');

        return view('projectmanagement::admin.workTrackings.edit', compact('work_types', 'workTracking'));
    }

    public function update(UpdateWorkTrackingRequest $request, WorkTracking $workTracking)
    {
        $workTracking->update($request->all());
        //$workTracking->permissions()->sync($request->input('permissions', []));

        return redirect()->route('projectmanagement.admin.work-trackings.index');
    }

    public function show(WorkTracking $workTracking)
    {
        abort_if(Gate::denies('work_tracking_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workTracking->load('work_type');

        return view('projectmanagement::admin.workTrackings.show', compact('workTracking'));
    }

    public function destroy(WorkTracking $workTracking)
    {
        abort_if(Gate::denies('work_tracking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workTracking->delete();

        return back();
    }

    public function massDestroy(MassDestroyWorkTrackingRequest $request)
    {
        WorkTracking::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function forceDelete(Request $request,$id)
    {
        //dd($request->all(),$id);
        $action = $request->action;

        if ($action == 'force_delete') {

            $workTracking = WorkTracking::onlyTrashed()->where('id', $id)->first();

            // force delete bug
            $workTracking->forceDelete();

        } else if ($action == 'restore') {
            //restore bug
            WorkTracking::onlyTrashed()->where('id', $id)->restore();
        }

        return back();

    }
}
