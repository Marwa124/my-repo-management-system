@extends('layouts.admin')
@section('content')
@inject('salaryTemplateModel', 'Modules\Payroll\Entities\SalaryTemplate')
<div class="row">
    @can('account_detail_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('hr.admin.account-details.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.accountDetail.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row d-flex ml-auto">
        {{-- <div style="" class="row d-flex ml-auto"> --}}
            <div class="col-lg-12">
                <select data-column="0" class="form-control filter-select" name="" id="">
                    <option value="0">Active Users</option>
                    <option value="1">Unactive Users</option>
                </select>
            </div>
        {{-- </div>
        <div style="" class="row d-flex ml-auto"> --}}
            {{-- <div class="col-md-6">
                <select data-column="1" class="form-control filter-deleted" name="" id="">
                    <option value="">Untrashed</option>
                    <option value="trashed">Trashed</option>
                </select>
            </div> --}}
        {{-- </div> --}}
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.accountDetail.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive" style="overflow-x: hidden !important;">
            <table class="display responsive nowrap table table-bordered table-striped table-hover datatable datatable-AccountDetail" style="width:100%">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.avatar') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.fullname') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.phone_number') }}
                        </th>
                        <th>
                            Banned
                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.joining_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.designation') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.department') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountDetail.fields.salary') }}
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accountDetails as $key => $accountDetail)
                    @if ($accountDetail)

                        <tr data-entry-id="{{ $accountDetail->id }}">
                            <td>

                            </td>
                            <td>
                                @if ($accountDetail->user_id == 36)

                                    {{-- {{$accountDetail->getMedia('avatar')->first()->getUrl('thumb')}} --}}
                                    @endif
                                @if($accountDetail->avatar)
                                    {{-- <a href="{{ str_replace('storage', 'public/storage', $accountDetail->avatar->getUrl()) }}" target="_blank">
                                        <img class="rounded-circle img-thumbnail d-flex m-auto"
                                        src="{{ str_replace('storage', 'public/storage', $accountDetail->avatar->getUrl('thumb')) }}">
                                    </a> --}}

                                    <a href="{{ str_replace('storage', 'storage/app/public', $accountDetail->avatar->getUrl()) }}" target="_blank">
                                        <img class="rounded-circle img-thumbnail d-flex m-auto"
                                        src="{{ str_replace('storage', 'storage/app/public', $accountDetail->avatar->getUrl('thumb')) }}">
                                    </a>
                                    {{-- <a href="{{ $accountDetail->avatar->getUrl() }}" target="_blank">
                                        <img class="rounded-circle img-thumbnail d-flex m-auto"
                                        src="{{ $accountDetail->avatar->getUrl('thumb') }}">
                                    </a> --}}
                                @else
                                    <a href="javascript:void(0)" style="display: inline-block">
                                        <img class="rounded-circle img-thumbnail"
                                        style="display: block;
                                            margin-left: auto;
                                            margin-right: auto;
                                            width: 30%;"
                                        src="{{ asset('images/default.png') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $accountDetail->fullname ?? '' }}
                            </td>
                            <td>
                                {{ $accountDetail->user->email ?? '' }}
                            </td>
                            <td>
                                {{ $accountDetail->mobile ?? '' }}
                            </td>
                            <td>
                                {{ $accountDetail->user->banned ?? '' }}
                            </td>
                            <td>
                                {{ $accountDetail->joining_date ?? '' }}
                            </td>
                            <td>
                                {{ $accountDetail->designation->designation_name ?? '' }}
                            </td>
                            <td>
                                {{ $accountDetail->designation->department->department_name ?? '' }}
                            </td>
                            <td>
                                <?php
                                    $designatonName = $accountDetail->designation;
                                    $salary = $designatonName ? $salaryTemplateModel::where('salary_grade', $designatonName->designation_name)->select('basic_salary')->first() : '';
                                ?>
                                {{  $salary ? 'EGY ' .number_format($salary->basic_salary, 0, ',', '.') : ''}}
                            </td>
                            <td>
                                <div class="defaultBtns">
                                    @can('account_detail_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('hr.admin.account-details.show', $accountDetail->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('account_detail_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('hr.admin.account-details.edit', $accountDetail->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('permission_access')
                                        {{-- <a href="{{ route("admin.permissions.index", $accountDetail->user_id) }}" class="btn btn-xs btn-warning"> --}}
                                        <a href="{{ route("admin.permissions.index", $accountDetail->id) }}" class="btn btn-xs btn-warning">
                                            {{ trans('cruds.permission.title') }}
                                        </a>
                                    @endcan

                                    @can('account_detail_delete')
                                        <form action="{{ route('hr.admin.account-details.destroy', $accountDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan
                                </div>
                                <div class="restoreDelete">
                                    @can('account_detail_delete')
                                    <form action="{{ route('hr.admin.account-details.forceDestroy', $accountDetail->id) }}" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{$accountDetail->id}}">
                                        <input type="hidden" name="action" value="restore">
                                        <input type="submit" class="btn btn-xs btn-success restore" value="Restore">
                                    </form>
                                    <form action="{{ route('hr.admin.account-details.forceDestroy', $accountDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{$accountDetail->id}}">
                                        <input type="hidden" name="action" value="force_delete">
                                        <input type="submit" class="btn btn-xs btn-danger forceDestroy" value="Force Delete">
                                    </form>
                                    @endcan
                                </div>



                            </td>

                        </tr>
                    @endif

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
        $('.restoreDelete').css('display', 'none');



  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
// @can('account_detail_delete')
//   let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
//   let deleteButton = {
//     text: deleteButtonTrans,
//     url: "{{ route('hr.admin.account-details.massDestroy') }}",
//     className: 'btn-danger',
//     action: function (e, dt, node, config) {
//       var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
//           return $(entry).data('entry-id')
//       });

//       if (ids.length === 0) {
//         alert('{{ trans('global.datatables.zero_selected') }}')

//         return
//       }

//       if (confirm('{{ trans('global.areYouSure') }}')) {
//         $.ajax({
//           headers: {'x-csrf-token': _token},
//           method: 'POST',
//           url: config.url,
//           data: { ids: ids, _method: 'DELETE' }})
//           .done(function () { location.reload() })
//       }
//     }
//   }
//   dtButtons.push(deleteButton)
// @endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
    scrollX : false,
  });
  let table = $('.datatable-AccountDetail:not(.ajaxTable)').DataTable({
        buttons: [dtButtons, 'colvis'],
    })

    // Hide columns
    table.columns( [5] ).visible( false );
    table.columns([5]).search( 0 ).draw(); // set a default load in datatable column (Active Users)


  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

  $('.filter-select').on('change', function () {
    table
        .column(5)
        .search($(this).val())
        .draw()
    });


    // $('.filter-deleted').on('change', function () {

    //   $('.defaultBtns').css('display', 'none');
    //   console.log($('.filter-deleted').val());

    //   if ($('.filter-deleted').val() != 1) {
    //     $('.restoreDelete').css('display', 'block');
    //     $('.defaultBtns').css('display', 'none');
    //   }else{
    //     $('.restoreDelete').css('display', 'none');
    //     $('.defaultBtns').css('display', 'block');
    //   }
    // table
    //     .column(8)
    //     .search($(this).val())
    //     .draw()
    // });
//   $(".filter-select").change(function(){
// // console.log($(this).val());
//     $.ajax({
//         type: 'GET',
//         url: "{{route('hr.admin.account-details.index')}}",
//         dataType: 'html',
//         data: {
//             selectFilter: $(this).val(),
//         },
//         success: function(data){
//             // console.log(data);
//             $('body').html(data);
//         }
//     })

//   })



})

</script>
@endsection
