@extends('layouts.admin')
@section('content')

    <div class=" card">
    <div class=" d-flex">

{{--        <div class=" col-sm-2 pb-3">--}}
{{--            <div class="card-body ">--}}
{{--                <span >{{$tickets->count()}}</span><br>--}}
{{--                <a class="float-left" id="all" type="button" >--}}
{{--                    All--}}
{{--                </a>--}}

{{--            </div>--}}
{{--        </div>--}}

        <div class=" col-sm-2 pb-3">
            <div class="card-body ">
                <span >{{$tickets->where('status','answered')->count()}}</span><br>
                <a class="float-left" id="answered" type="button" >
                    Answered Tickets
                </a>

            </div>
        </div>

        <div class=" col-sm-2 pb-3">
            <div class="card-body ">
                <span >{{$tickets->where('status','in_progress')->count()}}</span><br>
                <a class="float-left" id="in_progress" type="button" >
                    In Progress Tickets
                </a>

            </div>
        </div>

        <div class=" col-sm-2 pb-3">
            <div class="card-body ">
                <span >{{$tickets->where('status','opened')->count()}}</span><br>
                <a class="float-left" id="opened" type="button" >
                    Open Tickets
                </a>

            </div>
        </div>

        <div class=" col-sm-2 pb-3">
            <div class="card-body ">
                <span >{{$tickets->where('status','closed')->count()}}</span><br>
                <a class="float-left" id="closed" type="button" >
                    Close Tickets
                </a>

            </div>
        </div>

        <div class=" col-sm-2 pb-3">
            <div class="card-body ">
                <span >{{$tickets->where('status','reopen')->count()}}</span><br>
                <a class="float-left" id="reopen" type="button" >
                    Reopen Tickets
                </a>

            </div>
        </div>
{{--        <div class="card col-sm-2 ">--}}
{{--            <div class="card-body">--}}

{{--                <a class="float-left" id="in_progress" type="button" >--}}
{{--                    In Progress--}}
{{--                </a>--}}
{{--                <span class="float-right">{{$projects->where('project_status','in_progress')->count().'/'.$projects->count()}}</span><br>--}}
{{--                <div class="progress" style="width: auto" >--}}
{{--                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{$projects->where('project_status','in_progress')->count()/$projects->count()*100}}%; " aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


    </div>
    </div>

    @can('ticket_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('projectmanagement.admin.tickets.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ticket.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Ticket">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.ticket_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.subject') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.project') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $key => $ticket)
                        <tr data-entry-id="{{ $ticket->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ticket->id ?? '' }}
                            </td>
                            <td>
                                <a href="{{ route('projectmanagement.admin.tickets.show', $ticket->id) }}">

                                    {{ $ticket->ticket_code ?? '' }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('projectmanagement.admin.tickets.show', $ticket->id) }}">
                                    {{ $ticket->subject ?? '' }}

                                </a>
                            </td>
                            <td>
                                {{ $ticket->project->name ?? '' }}
                            </td>
                            <td>
                                {{ $ticket->status ? ucfirst($ticket->status) : '' }}
                            </td>

                            <td>
                                @can('ticket_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('projectmanagement.admin.tickets.show', $ticket->id) }}">
{{--                                        {{ trans('global.view') }}--}}
                                        <span class="fa fa-eye"></span>
                                    </a>
                                @endcan

                                @can('ticket_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('projectmanagement.admin.tickets.edit', $ticket->id) }}">
                                        <span class="fa fa-pencil-square-o"></span>
{{--                                        {{ trans('global.edit') }}--}}
                                    </a>
                                @endcan

                                @can('ticket_delete')
                                    <form action="{{ route('projectmanagement.admin.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

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
@can('ticket_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('projectmanagement.admin.tickets.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
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
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-Ticket:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  $('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value
      table
        .column($(this).parent().index())
        .search(value, strict)
        .draw()
  });

        $('#all').on('click',function () {
            table
                .columns( 5 )
                .search( '' )
                .draw()
        })

        $('#answered').on('click',function () {
            table
                .columns( 5 )
                .search( 'answered' )
                .draw()
        })

        $('#in_progress').on('click',function () {
            table
                .columns( 5 )
                .search( 'in progress' )
                .draw()
        })

        $('#opened').on('click',function () {
            table
                .columns( 5 )
                .search( 'opened' )
                .draw()
        })

        $('#closed').on('click',function () {
            table
                .columns( 5 )
                .search( 'closed' )
                .draw()
        })

        $('#reopen').on('click',function () {
            table
                .columns( 5 )
                .search( 'reopen' )
                .draw()
        })
})

</script>
@endsection
