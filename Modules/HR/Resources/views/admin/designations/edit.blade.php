@extends('layouts.admin')
@section('styles')
<style>
 .switch{
    width:30px;
    height:17px;
    background:#E5E5E5;
    z-index:0;
    margin:0;
    padding:0;
    appearance:none;
    border:none;
    cursor:pointer;
    position:relative;
    border-radius:100px;
}
.switch:before{
    content: '';
    position: absolute;
    left: 4px;
    top: 3px;
    width: 11px;
    height: 11px;
    background: #FFFFFF;
    z-index: 1;
    border-radius: 95px;
}
.switch:after{
    content: '';
    width: 11px;
    height: 11px;
    border-radius: 86px;
    z-index: 2;
    background: #FFFFFF;
    position: absolute;
    transition-duration: 400ms;
    top: 3px;
    left: 4px;
    box-shadow: 0 2px 5px #999999;
}
.switchOn, .switchOn:before{
    background:#4cd964; !important;
}
.switchOn:after{
    left:15px;
}
</style>
@endsection
@section('content')

@inject('permissionGroupModel', 'App\Models\PermissionGroup')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.designation.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("hr.admin.designations.update", [$designation->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="department_id" class="required">{{ trans('cruds.designation.fields.department') }}</label>
                <select class="form-control select2 {{ $errors->has('department') ? 'is-invalid' : '' }}" name="department_id" id="department_id">
                    @foreach($departments as $id => $department)
                        <option value="{{ $id }}" {{ (old('department_id') ? old('department_id') : $designation->department->id ?? '') == $id ? 'selected' : '' }}>{{ $department }}</option>
                    @endforeach
                </select>
                @if($errors->has('department'))
                    <div class="invalid-feedback">
                        {{ $errors->first('department') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designation.fields.department_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="designation_name">{{ trans('cruds.designation.fields.designation_name') }}</label>
                <input class="form-control {{ $errors->has('designation_name') ? 'is-invalid' : '' }}" type="text" name="designation_name" id="designation_name" value="{{ old('designation_name', $designation->designation_name) }}" required>
                @if($errors->has('designation_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('designation_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designation.fields.designation_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="designation_name_ar">{{ trans('cruds.designation.fields.designation_name_ar') }}</label>
                <input class="form-control {{ $errors->has('designation_name_ar') ? 'is-invalid' : '' }}" type="text" name="designation_name_ar" id="designation_name_ar" value="{{ old('designation_name_ar', $designation->designation_name_ar) }}" required>
                @if($errors->has('designation_name_ar'))
                    <div class="invalid-feedback">
                        {{ $errors->first('designation_name_ar') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="designation_leader_id">Select Designation Leader</label>
                <select class="form-control select2 {{ $errors->has('leaderId') ? 'is-invalid' : '' }}" name="designation_leader_id" id="designation_leader_id">
                    @foreach($users as $id => $user)
                        <option value="{{ $id }}" {{ ($designation->designation_leader_id == $id) ? 'selected' : '' }}>{{ $user }}</option>
                    @endforeach
                </select>
                @if($errors->has('leaderId'))
                    <div class="invalid-feedback">
                        {{ $errors->first('leaderId') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designation.fields.department_helper') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
                <a class="btn btn-secondary" href="{{url()->previous()}}">
                    {{ trans('global.back') }}
                </a>
            </div>

            <div class="pt-2">
                <button type="button" class="btn btn-dark waves-effect btn-sm btn-toggle-all-permissions">toggle</button>
                <button type="button" class="btn btn-dark waves-effect btn-sm">add_all</button>
            </div>

                @foreach ($permissions as $index => $group)
                <?php
                    $permissionsGroup = $permissionGroupModel::find($index);
                ?>
                <div class="wrapper-group">
                    <label for="">{{$permissionsGroup->name}}</label> <br>

                    <div class="actions mb-2">
                        <button
                            type="button"
                            class="btn btn-dark waves-effect btn-sm btn-toggle-permissions-in-group"
                        >{{trans('toggle')}}</button>
                        <button
                            type="button"
                            class="btn btn-dark waves-effect btn-sm add_all_btn"
                            data-group='{{$group}}'
                        >{{trans('add_all')}}</button>
                    </div>

                    <div class="row">
                        @foreach ($group as $key => $item)
                        <div class="col-md-3">
                            <div class="custom-control custom-switch">
                                <label>
                                    <input
                                    type="checkbox"
                                    class="checkbox permissionCheck"
                                    {{ (in_array($item->name, $userPermissions) == $item->name) ? 'checked' : ''}}
                                    value="{{$item->name}}"
                                    name="permissions[]"
                                    hidden
                                    >
                                    <div class="d-flex align-items-center">
                                        <div class="switch"></div>
                                        <div class="pl-1" style="cursor: pointer !important;">{{$item->name}}</div>
                                    </div>
                                </label>
                            </div>
                        </div> <!-- End Col-md-3 -->
                        @endforeach
                    </div> <br> <!-- End Row -->
                </div>
                @endforeach

        </form>
    </div>
</div>

@endsection

@section('scripts')
{{-- @parent --}}
<script>
    $(document).ready(function () {

        $('.switch').click(function(){
            $(this).toggleClass("switchOn");
        });

        $('.btn-toggle-permissions-in-group').click(function(){
            $(this).closest('.wrapper-group').find('.switch').toggleClass("switchOn");
            var $inputCheck = $(this).closest('.wrapper-group').find('.checkbox');
            if ($inputCheck.attr('checked')) {
                $inputCheck.removeAttr('checked');
            } else {
                $inputCheck.attr('checked', true);
            }
        });
        $('.add_all_btn').click(function() {
            $(this).closest('.wrapper-group').find('.switch').addClass("switchOn");
            $(this).closest('.wrapper-group').find('.checkbox').attr("checked", true);

            $(this).data('group');
        });


        $('input[type=checkbox]:checked').each(function () {
            $(this).closest('.custom-switch').find('.switch').addClass('switchOn');
            console.log($(this).closest('.custom-switch').find('.switch').addClass('switchOn'));
        });


    });

</script>
@endsection
