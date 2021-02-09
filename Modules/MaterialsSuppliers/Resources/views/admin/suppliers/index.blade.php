@extends('layouts.admin')
@section('content')
@can('supplier_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('materialssuppliers.admin.suppliers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.supplier.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.supplier.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Supplier">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.supplier.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.supplier.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.supplier.fields.mobile') }}
                        </th>
                        <th>
                            {{ trans('cruds.supplier.fields.phone') }}
                        </th>
                        <th>
                            {{ trans('cruds.supplier.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.supplier.fields.address') }}
                        </th>
                        <th>
                            {{ trans('cruds.supplier.fields.customer_group') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search">
                                @foreach($customer_groups as $key => $item)
                                    <option value="{{ $item->type }}">{{ $item->type }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $key => $supplier)
                        <tr data-entry-id="{{ $supplier->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $supplier->id ?? '' }}
                            </td>
                            <td>
                                {{ $supplier->name ?? '' }}
                            </td>
                            <td>
                                {{ $supplier->mobile ?? '' }}
                            </td>
                            <td>
                                {{ $supplier->phone ?? '' }}
                            </td>
                            <td>
                                {{ $supplier->email ?? '' }}
                            </td>
                            <td>
                                {{ $supplier->address ?? '' }}
                            </td>
                            <td>
                                {{ $supplier->customer_group->type ?? '' }}
                            </td>
                            <td>
                                @can('supplier_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('materialssuppliers.admin.suppliers.show', $supplier->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('supplier_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('materialssuppliers.admin.suppliers.edit', $supplier->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('supplier_delete')
                                    <form action="{{ route('materialssuppliers.admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('supplier_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('materialssuppliers.admin.suppliers.massDestroy') }}",
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
  let table = $('.datatable-Supplier:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
})

</script>
@endsection