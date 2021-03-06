<?php

namespace Modules\HR\Http\Controllers\Admin;

use Modules\HR\Http\Controllers\Controller;
use Modules\HR\Http\Requests\Destroy\MassDestroyHolidayRequest;
use Modules\HR\Http\Requests\Store\StoreHolidayRequest;
use Modules\HR\Http\Requests\Update\UpdateHolidayRequest;
use Modules\HR\Entities\Holiday;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class HolidaysController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('holiday_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Holiday::select(sprintf('%s.*', (new Holiday)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = '';  //holiday_show
                $editGate      = 'holiday_edit';
                $deleteGate    = 'holiday_delete';
                $modalId       = 'hr.';
                $crudRoutePart = 'holidays';

                return view('partials.datatablesActions', compact(
                    'viewGate',                   
                     'editGate',
                    'deleteGate',
                    'modalId',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->addColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->addColumn('start_date', function ($row) {
                return $row->start_date ? $row->start_date : "";
            });
            $table->addColumn('end_date', function ($row) {
                return $row->end_date ? $row->end_date : "";
            });

            $table->rawColumns(['placeholder', 'actions']);

            return $table->make(true);
        }

        return view('hr::admin.holidays.index');
    }

    public function create()
    {
        abort_if(Gate::denies('holiday_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('hr::admin.holidays.create');
    }

    public function store(StoreHolidayRequest $request)
    {
        $holiday = Holiday::create($request->all());

        $message = array(
            'message'    =>  ' Created Successfully',
            'alert-type' =>  'success'
        );
        $flashMsg = flash($message['message'], $message['alert-type']);

        return redirect()->route('hr.admin.holidays.index')->with($flashMsg);
    }

    public function edit(Holiday $holiday)
    {
        abort_if(Gate::denies('holiday_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('hr::admin.holidays.edit', compact('users', 'holiday'));
    }

    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        $holiday->update($request->all());

        $message = array(
            'message'    =>  ' Updated Successfully',
            'alert-type' =>  'success'
        );
        $flashMsg = flash($message['message'], $message['alert-type']);

        return redirect()->route('hr.admin.holidays.index')->with($flashMsg);
    }

    public function show(Holiday $holiday)
    {
        abort_if(Gate::denies('holiday_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('hr::admin.holidays.show', compact('holiday'));
    }

    public function destroy(Holiday $holiday)
    {
        abort_if(Gate::denies('holiday_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $holiday->delete();

        return back();
    }

    public function massDestroy(MassDestroyHolidayRequest $request)
    {
        Holiday::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
