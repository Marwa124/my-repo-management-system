@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.workTracking.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("projectmanagement.admin.work-trackings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="subject_en">{{ trans('cruds.workTracking.fields.subject_en') }}</label>
                    <input class="form-control {{ $errors->has('subject_en') ? 'is-invalid' : '' }}" type="text" name="subject_en" id="subject_en" value="{{ old('subject_en') }}" required>
                    @if($errors->has('subject_en'))
                        <div class="invalid-feedback">
                            {{ $errors->first('subject_en') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.subject_en_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="subject_ar">{{ trans('cruds.workTracking.fields.subject_ar') }}</label>
                    <input class="form-control {{ $errors->has('subject_ar') ? 'is-invalid' : '' }}" type="text" name="subject_ar" id="subject_ar" value="{{ old('subject_ar') }}" required>
                    @if($errors->has('subject_ar'))
                        <div class="invalid-feedback">
                            {{ $errors->first('subject_ar') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.subject_ar_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="work_type_id">{{ trans('cruds.workTracking.fields.work_type') }}</label>
                    <select class="form-control select2 {{ $errors->has('work_type') ? 'is-invalid' : '' }}" name="work_type_id" id="work_type_id" required>
                        <option value="" selected disabled>{{trans('global.pleaseSelect')}}</option>
                        @foreach($work_types as $id => $work_type)
                            <option value="{{ $id }}" {{ old('work_type_id') == $id ? 'selected' : '' }}>{{ trans('cruds.timeWorkType.'.$work_type) }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('work_type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('work_type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.work_type_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="achievement">{{ trans('cruds.workTracking.fields.achievement') }}</label>
                    <input class="form-control {{ $errors->has('achievement') ? 'is-invalid' : '' }}" type="number" name="achievement" id="achievement" value="{{ old('achievement', '') }}" step="1">
                    @if($errors->has('achievement'))
                        <div class="invalid-feedback">
                            {{ $errors->first('achievement') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.achievement_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="start_date">{{ trans('cruds.workTracking.fields.start_date') }}</label>
                    <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" required>
                    @if($errors->has('start_date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('start_date') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.start_date_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="end_date">{{ trans('cruds.workTracking.fields.end_date') }}</label>
                    <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text" name="end_date" id="end_date" value="{{ old('end_date') }}" required>
                    @if($errors->has('end_date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('end_date') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.end_date_helper') }}</span>
                </div>

                <div class="form-group">
                    {{--<label for="notify_work_achive">{{ trans('cruds.workTracking.fields.notify_work_achive') }}</label>--}}
                    {{--<input class="form-control {{ $errors->has('notify_work_achive') ? 'is-invalid' : '' }}" type="text" name="notify_work_achive" id="notify_work_achive" value="{{ old('notify_work_achive', '') }}">--}}

                    <input type="checkbox" name="notify_work_achive" id="notify_work_achive" value="{{ old('notify_work_achive','on') }}"
                            {{ old('notify_work_achive') ? 'checked':''}}
                    /> {{ trans('cruds.workTracking.fields.notify_work_achive') }}

                    @if($errors->has('notify_work_achive'))
                        <div class="invalid-feedback">
                            {{ $errors->first('notify_work_achive') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.notify_work_achive_helper') }}</span>
                </div>
                <div class="form-group">
                    {{--<label for="notify_work_not_achive">{{ trans('cruds.workTracking.fields.notify_work_not_achive') }}</label>--}}

                    <input type="checkbox" name="notify_work_not_achive" id="notify_work_not_achive" value="{{ old('notify_work_not_achive','on') }}"
                            {{ old('notify_work_not_achive') ? 'checked':''}}
                    /> {{ trans('cruds.workTracking.fields.notify_work_not_achive') }}

{{--                    <input class="form-control {{ $errors->has('notify_work_not_achive') ? 'is-invalid' : '' }}" type="text" name="notify_work_not_achive" id="notify_work_not_achive" value="{{ old('notify_work_not_achive', '') }}">--}}
                    @if($errors->has('notify_work_not_achive'))
                        <div class="invalid-feedback">
                            {{ $errors->first('notify_work_not_achive') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.workTracking.fields.notify_work_not_achive_helper') }}</span>
                </div>
{{--                <div class="form-group">--}}
{{--                    <label for="email_send">{{ trans('cruds.workTracking.fields.email_send') }}</label>--}}
{{--                    <input class="form-control {{ $errors->has('email_send') ? 'is-invalid' : '' }}" type="text" name="email_send" id="email_send" value="{{ old('email_send', 'no') }}">--}}
{{--                    @if($errors->has('email_send'))--}}
{{--                        <div class="invalid-feedback">--}}
{{--                            {{ $errors->first('email_send') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <span class="help-block">{{ trans('cruds.workTracking.fields.email_send_helper') }}</span>--}}
{{--                </div>--}}

{{--                <div class="form-group">--}}
{{--                    <label for="account_id">{{ trans('cruds.workTracking.fields.account') }}</label>--}}
{{--                    <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id">--}}
{{--                        @foreach($accounts as $id => $account)--}}
{{--                            <option value="{{ $id }}" {{ old('account_id') == $id ? 'selected' : '' }}>{{ $account }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    @if($errors->has('account'))--}}
{{--                        <div class="invalid-feedback">--}}
{{--                            {{ $errors->first('account') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <span class="help-block">{{ trans('cruds.workTracking.fields.account_helper') }}</span>--}}
{{--                </div>--}}

{{--                <div class="checkbox c-checkbox col-md-6 {{$key % 2 == 1 ? 'float-right':'float-left'}}">--}}
{{--                    <input type="checkbox" name="accounts[]" value="{{ $account->id}}"--}}
{{--                    @forelse($task->accountDetails as $accountDetail)--}}
{{--                        {{ $accountDetail->id == $account->id ? 'checked':''}}--}}

{{--                        @empty--}}
{{--                        @endforelse--}}

{{--                    /> {{ $account->fullname}}<br/>--}}
{{--                    <hr class="mt-sm mb-sm"/>--}}

{{--                </div>--}}
            </div>

            <div class="form-group col-md-12">
                <label for="description_en">{{ trans('cruds.workTracking.fields.description_en') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description_en') ? 'is-invalid' : '' }}" name="description_en" id="description_en">{{ old('description_en') }}</textarea>
                @if($errors->has('description_en'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description_en') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.workTracking.fields.description_en_helper') }}</span>
            </div>
            <div class="form-group col-md-12">
                <label for="description_ar">{{ trans('cruds.workTracking.fields.description_ar') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description_ar') ? 'is-invalid' : '' }}" name="description_ar" id="description_ar">{{ old('description_ar') }}</textarea>
                @if($errors->has('description_ar'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description_ar') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.workTracking.fields.description_ar_helper') }}</span>
            </div>
            <div class="form-group col-md-12">
                <button class="btn btn-danger float-right" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

