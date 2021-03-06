@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        @can('task_tag_create')
            <div class="col-lg-6">
                <a class="btn btn-success" href="{{ route('projectmanagement.admin.task-tags.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.taskTag.title_singular') }}
                </a>
            </div>
        @endcan
        @can('task_tag_delete')
                <div class="col-lg-6" style="display:flex; justify-content:flex-end">
                    <a class="btn btn-{{$trashed ? 'info' : 'danger'}}"
                       href="{{$trashed ? route('projectmanagement.admin.task-tags.index') : route('projectmanagement.admin.task-tags.trashed.index')}}">
                        {{ $trashed ? trans('cruds.status.active') : trans('cruds.status.trashed') }} {{ trans('cruds.taskTag.title') }}
                    </a>
                </div>
        @endcan
    </div>
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.taskTag.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-TaskTag">
                    <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('cruds.taskTag.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.taskTag.fields.name_en') }}
                        </th>
                        <th>
                            {{ trans('cruds.taskTag.fields.name_ar') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($taskTags as $key => $taskTag)
                        <tr data-entry-id="{{ $taskTag->id }}">
                            <td>
                            </td>
                            <td>
                                {{ $taskTag->id ?? '' }}
                            </td>
                            <td>
                                {{ $taskTag->name_en ?? '' }}
                            </td>
                            <td>
                                {{ $taskTag->name_ar ?? '' }}
                            </td>
                            <td>
                                @if(!$trashed)
                                    {{--                                    @can('task_tag_show')--}}
                                    {{--                                        <a class="btn btn-xs btn-primary" href="{{ route('projectmanagement.admin.task-tags.show', $taskTag->id) }}">--}}
                                    {{--                                            <span class="fa fa-eye"></span>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    @endcan--}}

                                    @can('task_tag_edit')
                                        <a class="btn btn-xs btn-info"
                                           href="{{ route('projectmanagement.admin.task-tags.edit', $taskTag->id) }}">
                                            <span class="fa fa-pencil-square-o"></span>
                                        </a>
                                    @endcan

                                    @can('task_tag_delete')
                                        <form
                                            action="{{ route('projectmanagement.admin.task-tags.destroy', $taskTag->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button title="Delete" class="btn btn-xs btn-danger" type="submit"><span class="fas fa-trash"></span></button>
                                        </form>
                                    @endcan
                                @else
                                    @can('task_tag_delete')
                                        <form
                                            action="{{ route('projectmanagement.admin.task-tags.forceDestroy', $taskTag->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="action" value="restore">
                                            <input type="submit" class="btn btn-xs btn-success"
                                                   value="{{ trans('global.restore') }}">
                                        </form>
                                        <form
                                            action="{{ route('projectmanagement.admin.task-tags.forceDestroy', $taskTag->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{trans('cruds.messages.task_tag_force_delete')}} \n{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="action" value="force_delete">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                   value="{{ trans('global.forcedelete') }}">
                                        </form>
                                    @endcan
                                @endif
                            </td>

                        </tr>
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
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @if(!$trashed)
                @can('task_tag_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('projectmanagement.admin.task-tags.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: {ids: ids, _method: 'DELETE'}
                        })
                            .done(function () {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan
            @endif
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[1, 'desc']],
                pageLength: 100,
            });
            let table = $('.datatable-TaskTag:not(.ajaxTable)').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })

    </script>
@endsection
