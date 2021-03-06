@extends('layouts.admin')
@section('content')
@can('purchase_payment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('materialssuppliers.admin.purchase-payments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.purchasePayment.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.purchasePayment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-PurchasePayment">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.purchasePayment.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.purchasePayment.fields.purchase') }}
                        </th>
                        <th>
                            {{ trans('cruds.purchasePayment.fields.payment_method') }}
                        </th>
                        <th>
                            {{ trans('cruds.purchasePayment.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.purchasePayment.fields.payment_date') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasePayments as $key => $purchasePayment)
                        <tr data-entry-id="{{ $purchasePayment->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $purchasePayment->id ?? '' }}
                            </td>
                            <td>
                                {{ $purchasePayment->purchase->reference_no ?? '' }}
                            </td>
                            <td>
                                {{ $purchasePayment->payment_method ?? '' }}
                            </td>
                            <td>
                                {{ $purchasePayment->amount ?? '' }}
                            </td>
                            <td>
                                {{ $purchasePayment->payment_date ?? '' }}
                            </td>
                            <td>
                                @can('purchase_payment_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('materialssuppliers.admin.purchase-payments.show', $purchasePayment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('purchase_payment_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('materialssuppliers.admin.purchase-payments.edit', $purchasePayment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('purchase_payment_delete')
                                    <form action="{{ route('materialssuppliers.admin.purchase-payments.destroy', $purchasePayment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button title="Delete" class="btn btn-xs btn-danger" type="submit"><span class="fas fa-trash"></span></button>
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
@can('purchase_payment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('materialssuppliers.admin.purchase-payments.massDestroy') }}",
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
  let table = $('.datatable-PurchasePayment:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection