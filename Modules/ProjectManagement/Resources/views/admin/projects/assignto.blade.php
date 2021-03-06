@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.project.title_singular') }} {{ trans('global.assign_to') }} {{ trans('cruds.employee.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("projectmanagement.admin.projects.storeAssignTo") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="project_id" value="{{$project->id}}"/>
            @forelse($department->departmentDesignations()->get() as $designation)
                <div class="">

{{--                    <label for="{{$designation->designation_name}}"><b>{{$designation->designation_name}}</b></label>--}}
{{--                    <hr class="mt-sm mb-sm"/>--}}
                    <div >
                        @php
                            $designation_key = 0;
                        @endphp
                        @forelse($designation->accountDetails()->get() as $key => $account)
                            @if($designation_key == 0)
                                <label for="{{$designation->designation_name}}"><b>{{$designation->designation_name}}</b></label>
                                <hr class="mt-sm mb-sm"/>
                                @php
                                    $designation_key++;
                                @endphp
                            @endif
                            <div class="checkbox c-checkbox col-md-6 {{$key % 2 == 1 ? 'float-right':'float-left'}}">

                                <input type="checkbox" name="accounts[]" value="{{ $account->id}}"
                                    @forelse($project->accountDetails as $accountDetail)
                                        {{ $accountDetail->id == $account->id ? 'checked':''}}

                                    @empty
                                    @endforelse

                                    /> {{ $account->fullname}}<br/>
                                <hr class="mt-sm mb-sm"/>
                            </div>

                        @empty

                        @endforelse
                    </div>
                </div>

                <div class="clearfix"></div>
            @empty
                <div class="form-group col-md-6">

                    {{trans('cruds.messages.no_designation_found_in_department_project')}}
                </div>
            @endforelse

            <div class="form-group">
                <button class="btn btn-danger float-right" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
