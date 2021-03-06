<?php

namespace Modules\Finance\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockRequest;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\AssignStock;
use App\Models\Stock;
use App\Models\StockCategory;
use App\Models\StockSubCategory;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\Designation;
use Symfony\Component\HttpFoundation\Response;

class StocksController extends Controller
{
    public function index()
    {

//        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


//        $all_stocks = Stock::all()->groupBy(['name','stock_sub_category_id']);
        $stocks = DB::table('stocks')->select(DB::raw('name,stock_sub_category_id,stock_category_id,SUM(total_stock) AS TotalStock'))
            ->groupBy(['name', 'stock_sub_category_id','stock_category_id'])
            ->where('deleted_at',null)
            ->get();

//        $stocks = StockCategory::all();

        foreach($stocks as $stock){
            $ids = Stock::where('name',$stock->name)->pluck('id');
            $assigned_stock =AssignStock::where('sub_category_id',$stock->stock_sub_category_id)->whereIn('stock_id',$ids)->sum('quantity');

            $stock->TotalStock -= $assigned_stock;

        }




        $main_stocks_categories = $stocks->groupBy('stock_category_id');

//                dd($main_stocks_categories);

//
//        $main_categories = StockCategory::whereIn('id',$main_stocks_categories)->get();

//        dump($stocks);
//        dump($stocks->groupBy('stock_category_id'));
//        dd($stocks->groupBy('stock_sub_category_id'));
//        foreach($stocks as $stock){
//            $stock->sub_categories();
//        }

        return view('finance::admin.stocks.index', compact('main_stocks_categories'));
    }

    public function create()
    {
//        abort_if(Gate::denies('stock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stock_sub_categories = StockSubCategory::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $stock_categories = StockCategory::all();

        return view('finance::admin.stocks.create', compact('stock_sub_categories','stock_categories'));
    }

    public function store(StoreStockRequest $request)
    {


        $stock_category = StockSubCategory::findOrFail($request->stock_sub_category_id);

        $data = [
            'stock_sub_category_id' => $request->stock_sub_category_id,
            'total_stock' => $request->total_stock,
            'buying_date' => $request->buying_date,
            'name' => $request->name,
            'stock_category_id' => $stock_category->stock_category->id,
        ];
        $stock = Stock::create($data);

        return redirect()->route('finance.admin.stocks.index');
    }

    public function edit($name, $sub_stock_category)
    {
//        abort_if(Gate::denies('stock_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stock = Stock::where([
            ['name', $name],
            ['stock_sub_category_id', $sub_stock_category],
        ])->get();

        if (count($stock) > 0) {

            $total_stock = $stock->sum('total_stock');
            $stock = $stock->first();

            $stock_sub_categories = StockSubCategory::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $stock_categories = StockCategory::all();


            return view('finance::admin.stocks.edit', compact('stock_sub_categories','total_stock', 'stock','stock_categories'));
        } else {
            abort(Response::HTTP_NOT_FOUND, '404 Not Found');
        }
    }

    public function update(UpdateStockRequest $request)
    {
        $stock = Stock::findOrFail($request->id);
        $stocks = Stock::where([
            ['name', $stock->name],
            ['stock_sub_category_id', $stock->stock_sub_category_id],
        ])->get();

        foreach($stocks as $stock){
            $stock->update([
               'name'       => $request->name,
               'buying_date'       => $request->buying_date,
               'stock_sub_category_id'       => $request->stock_sub_category_id,
            ]);
        }

        return redirect()->route('finance.admin.stocks.index');
    }

    public function destroy($name, $sub_stock_category)
    {
//        abort_if(Gate::denies('stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       Stock::where([
            ['name', $name],
            ['stock_sub_category_id', $sub_stock_category],
        ])->delete();

        return back();
    }

    public function history()
    {
//        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $stock_categories = StockCategory::all();


        return view('finance::admin.stocks.history', compact('stock_categories'));
    }
    public function history_search_result(Request $request)
    {
//        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'stock_sub_category_id'  => 'required|integer|exists:stock_sub_categories,id',
        ]);
        $stock_categories = StockCategory::all();
        $stocks           = Stock::where('stock_sub_category_id',$request->stock_sub_category_id)->get();
        $category         = $request->stock_sub_category_id;
        return view('finance::admin.stocks.history', compact('stock_categories','stocks','category'));
    }
    public function stocks_history_edit($id)
    {
//        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $stock = Stock::findOrFail($id);
        $stock_categories = StockCategory::all();

        return view('finance::admin.stocks.stocks_history_edit', compact('stock','stock_categories'));
    }
    public function stocks_history_update(Request $request)
    {

//        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
           'id'    => 'required|integer',
           'name'  => 'required|string',
           'total_stock'  => 'required|numeric',
           'buying_date'  => 'required|date',
           'stock_sub_category_id'  => 'required|integer|exists:stock_sub_categories,id',
        ]);
        $stock = Stock::findOrFail($request->id);
        $sub_stock_category = StockSubCategory::findOrFail($request->stock_sub_category_id);
        $stock->update([
            'name'                    => $request->name,
            'total_stock'             => $request->total_stock,
            'buying_date'             => $request->buying_date,
            'stock_sub_category_id'   => $request->stock_sub_category_id,
            'stock_category_id'       => $sub_stock_category->stock_category->id,
        ]);

        return redirect()->route('finance.admin.stocks.history');
    }

    public function stocks_history_destroy($id)
    {
//        abort_if(Gate::denies('stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Stock::findOrFail($id)->delete();

        return redirect()->route('finance.admin.stocks.history');
    }




    public function report()
    {
        //        abort_if(Gate::denies('stocks'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designations = Designation::all();
        $stock_categories = StockCategory::all();
        return view('finance::admin.stocks.report', compact('designations','stock_categories'));
    }


    public function report_result(Request $request)
    {
        //        abort_if(Gate::denies('stocks'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request->item_id != null){
            $request->validate([
                'item_id'  => 'integer|exists:stocks,id'
            ]);
            $stock = Stock::findOrFail($request->item_id);
            $stock_ids = Stock::where('name',$stock->name)->get()->pluck('id');
            $stocks = Stock::where('name',$stock->name)->where('stock_sub_category_id',$stock->stock_sub_category_id)->get();
            $assigned_stocks = AssignStock::whereIn('stock_id',$stock_ids)->where('sub_category_id',$stock->stock_sub_category_id)->get();
            $cat_name = StockSubCategory::findOrFail($stock->stock_sub_category_id)->name;
            $stock_name = $stock->name;
            $id = $stock->id;
            $loadview = view('finance::admin.stocks.ajaxload_report_data', compact('stocks','assigned_stocks','cat_name','stock_name','id'))->render();

        }else{
            $request->validate([
                'start'  => 'date',
                'end'    => 'date',
            ]);
            $start = $request->start;
            $end   = $request->end;
            $stocks = Stock::where('buying_date','>=',$request->start)->where('buying_date','<=',$request->end)->get();
            $assigned_stocks = AssignStock::where('assign_date','>=',$request->start)->where('assign_date','<=',$request->end)->get();
            $sub_stocks = StockSubCategory::all();
            $loadview = view('finance::admin.stocks.ajaxload_report_data', compact('stocks','assigned_stocks','start','end','sub_stocks'))->render();

        }


        return response()->json($loadview, Response::HTTP_CREATED);

    }

    public function get_items(Request $request)
    {
        //        abort_if(Gate::denies('stocks'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'id'  =>'required|integer|exists:stock_sub_categories,id'
        ]);
        $items = Stock::where('stock_sub_category_id',$request->id)->get()->unique('name');

        $loadview = view('finance::admin.stocks.ajaxload_items_data', compact('items'))->render();
        return response()->json($loadview, Response::HTTP_CREATED);

    }


    public function pdf($id)
    {

//        abort_if(Gate::denies('stocks'), Response::HTTP_FORBIDDEN, trans('global.forbidden_page'));


            $stock = Stock::findOrFail($id);
            $stock_ids = Stock::where('name',$stock->name)->get()->pluck('id');
            $stocks = Stock::where('name',$stock->name)->where('stock_sub_category_id',$stock->stock_sub_category_id)->get();
            $assigned_stocks = AssignStock::whereIn('stock_id',$stock_ids)->where('sub_category_id',$stock->stock_sub_category_id)->get();
            $cat_name = StockSubCategory::findOrFail($stock->stock_sub_category_id)->name;
            $stock_name = $stock->name;
            $id = $stock->id;


        $title = $stock->name . '-assigned_stock.pdf'  ?? '-assigned_stock.pdf' ;
        $compact = [
            'stocks'   => $stocks,
            'assigned_stocks' => $assigned_stocks,
            'cat_name' => $cat_name,
            'stock_name' => $stock_name,
            'id' => $id,
        ];

        $view = 'finance::admin.stocks.pdf';
        download_pdf($view,$compact,$title);


        return abort(Response::HTTP_FORBIDDEN, trans('global.forbidden_page_not_allow_to_you'));

    }


    public function pdf_period($start,$end)
    {

//        abort_if(Gate::denies('stocks'), Response::HTTP_FORBIDDEN, trans('global.forbidden_page'));

        if($start == null || empty($start) ||$end == null || empty($end) ){
            return abort(Response::HTTP_FORBIDDEN, trans('global.forbidden_page_not_allow_to_you'));
        }


        $stocks = Stock::where('buying_date','>=',$start)->where('buying_date','<=',$end)->get();
        $assigned_stocks = AssignStock::where('assign_date','>=',$start)->where('assign_date','<=',$end)->get();
        $sub_stocks = StockSubCategory::all();



        $title = $start.'_'.$end . '_assigned_stock.pdf'  ?? 'assigned_stock.pdf' ;
        $compact = [
            'stocks'   => $stocks,
            'assigned_stocks' => $assigned_stocks,
            'sub_stocks' => $sub_stocks,
        ];

        $view = 'finance::admin.stocks.pdf_period';
        download_pdf($view,$compact,$title);


        return abort(Response::HTTP_FORBIDDEN, trans('global.forbidden_page_not_allow_to_you'));

    }

}
