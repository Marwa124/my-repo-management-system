<?php

namespace Modules\HR\Http\Controllers\Admin;

use Modules\HR\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Modules\HR\Http\Requests\Destroy\MassDestroyOvertimeRequest;
use Modules\HR\Http\Requests\Store\StoreOvertimeRequest;
use Modules\HR\Http\Requests\Update\UpdateOvertimeRequest;
use Modules\HR\Entities\Overtime;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OvertimeController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('overtime_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $overtimes = Overtime::all();

        return view('hr::admin.overtimes.index', compact('overtimes'));
    }

    public function create()
    {
        abort_if(Gate::denies('overtime_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = [];
        foreach (User::where('banned', 0)->get() as $key => $value) {
            $users[] = $value->accountDetail()->where('employment_id', '!=', null)->pluck('fullname', 'user_id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('hr::admin.overtimes.create', compact('users'));
    }

    public function store(StoreOvertimeRequest $request)
    {
        $overtime = Overtime::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $overtime->id]);
        }

        $message = array(
            'message'    =>  ' Created Successfully',
            'alert-type' =>  'success'
        );
        $flashMsg = flash($message['message'], $message['alert-type']);

        return redirect()->route('hr.admin.overtimes.index')->with($flashMsg);
    }

    public function edit(Overtime $overtime)
    {
        abort_if(Gate::denies('overtime_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = [];
        foreach (User::where('banned', 0)->get() as $key => $value) {
            $users[] = $value->accountDetail()->where('employment_id', '!=', null)->pluck('fullname', 'user_id')->prepend(trans('global.pleaseSelect'), '');
        }
        
        $overtime->load('user');

        return view('hr::admin.overtimes.edit', compact('users', 'overtime'));
    }

    public function update(UpdateOvertimeRequest $request, Overtime $overtime)
    {
        $overtime->update($request->all());
        
        $message = array(
            'message'    =>  ' Updated Successfully',
            'alert-type' =>  'success'
        );
        $flashMsg = flash($message['message'], $message['alert-type']);

        return redirect()->route('hr.admin.overtimes.index')->with($flashMsg);
    }

    public function show(Overtime $overtime)
    {
        abort_if(Gate::denies('overtime_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $overtime->load('user');

        return view('hr::admin.overtimes.show', compact('overtime'));
    }

    public function destroy(Overtime $overtime)
    {
        abort_if(Gate::denies('overtime_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $overtime->forceDelete();

        return back();
    }

    public function massDestroy(MassDestroyOvertimeRequest $request)
    {
        Overtime::whereIn('id', request('ids'))->forceDelete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('overtime_create') && Gate::denies('overtime_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Overtime();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
