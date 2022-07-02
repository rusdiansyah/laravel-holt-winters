<?php

namespace App\Http\Controllers;

use App\Models\RiceByStock;
use App\Models\RiceType;
use Illuminate\Http\Request;

use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RiceByStockController extends Controller
{
    private $folder = 'rice_by_stock';

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = RiceByStock::with(['type']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn-edit btn btn-info btn-sm">Edit</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn-delete btn btn-danger btn-sm">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $list_type = RiceType::get();

        return view($this->folder . '.index', compact('list_type'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $year_month = explode('-', $request->monthYear);
        // dd($year_month);
        $year = $year_month[0];
        $month = $year_month[1];
        $rice_type_id = $request->rice_type_id;

        $validated = $request->validate([
            'monthYear' => 'required',
            'rice_type_id' => [
                'required',
                Rule::unique('rice_by_stocks')->where(function ($query) use ($year, $month) {
                    return $query->where('year', $year)
                        ->where('month', $month);
                })->ignore($request->id),
            ],
            'stock' => 'required',
        ], [
            'monthYear.required' => 'Year Month is required',
            'rice_type_id.required' => 'Rice Type is required',
            'rice_type_id.unique' => 'Rice Type sudah ada pada tahun dan bulan tsb.',
            'stock.required' => 'Stock is required',
        ]);
        // dd($year);
        $data['rice_type_id'] = $request->rice_type_id;
        $data['stock'] = $request->stock;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['user_id'] = Auth::user()->id;

        $data = RiceByStock::updateOrCreate(
            [
                'id' => $id
            ],
            $data
        );


        return Response()->json($data);
    }



    public function edit($id)
    {
        $data = RiceByStock::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        RiceByStock::find($id)->delete();
        return response()->json(
            [
                'success' => 'Rice Type deleted successfuly.'
            ]
        );
    }
}
