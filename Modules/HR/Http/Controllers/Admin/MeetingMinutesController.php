<?php

namespace Modules\HR\Http\Controllers\Admin;

use Modules\HR\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Modules\HR\Entities\AccountDetail;
use Modules\HR\Http\Requests\Destroy\MassDestroyMeetingMinuteRequest;
use Modules\HR\Http\Requests\Store\StoreMeetingMinuteRequest;
use Modules\HR\Http\Requests\Update\UpdateMeetingMinuteRequest;
use Modules\HR\Entities\MeetingMinute;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class MeetingMinutesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('meeting_minute_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingMinutes = MeetingMinute::all();

        return view('hr::admin.meetingMinutes.index', compact('meetingMinutes'));
    }

    public function create()
    {
        abort_if(Gate::denies('meeting_minute_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::fetchUnbannedUsers();

        return view('hr::admin.meetingMinutes.create', compact('users'));
    }

    public function store(StoreMeetingMinuteRequest $request)
    {
        $meetingMinute = MeetingMinute::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $meetingMinute->id]);
        }

        $message = array(
            'message'    =>  ' Created Successfully',
            'alert-type' =>  'success'
        );
        $flashMsg = flash($message['message'], $message['alert-type']);

        return redirect()->route('hr.admin.meeting-minutes.index')->with($flashMsg);
    }

    public function edit(MeetingMinute $meetingMinute)
    {
        abort_if(Gate::denies('meeting_minute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::fetchUnbannedUsers();

        $attendances = [];
        foreach ($meetingMinute->attendees as $key => $value) {
            $user = AccountDetail::where('user_id', $value)->select('user_id')->first()->user_id;
            array_push($attendances, $user);
        }
        $meetingMinute->load('user');

        return view('hr::admin.meetingMinutes.edit', compact('users', 'meetingMinute', 'attendances'));
    }

    public function update(UpdateMeetingMinuteRequest $request, MeetingMinute $meetingMinute)
    {
        $meetingMinute->update($request->all());

        $message = array(
            'message'    =>  ' Updated Successfully',
            'alert-type' =>  'success'
        );
        $flashMsg = flash($message['message'], $message['alert-type']);

        return redirect()->route('hr.admin.meeting-minutes.index')->with($flashMsg);
    }

    public function show(MeetingMinute $meetingMinute)
    {
        abort_if(Gate::denies('meeting_minute_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingMinute->load('user');

        return view('hr::admin.meetingMinutes.show', compact('meetingMinute'));
    }

    public function destroy(MeetingMinute $meetingMinute)
    {
        abort_if(Gate::denies('meeting_minute_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingMinute->delete();

        return back();
    }

    public function massDestroy(MassDestroyMeetingMinuteRequest $request)
    {
        MeetingMinute::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('meeting_minute_create') && Gate::denies('meeting_minute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MeetingMinute();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
