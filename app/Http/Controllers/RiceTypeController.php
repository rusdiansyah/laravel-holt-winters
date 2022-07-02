<?php

namespace App\Http\Controllers;

use App\Models\RiceType;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class RiceTypeController extends Controller
{
    private $folder = 'rice_type';

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = RiceType::select('*');
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
        return view($this->folder . '.index');
    }


    public function create()
    {
        return view($this->folder . '.create');
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $validated = $request->validate([
            'title' => 'required|unique:rice_types|max:100',
        ], [
            'title.required' => 'Title is required',
            'title.unique' => 'Title is already exsist.',
            'title.max' => 'Title Max 100 Characters'
        ]);
        $validated['user_id'] = Auth::user()->id;

        $data = RiceType::updateOrCreate(
            [
                'id' => $id
            ],
            $validated
        );


        return Response()->json($data);
    }



    public function edit($id)
    {
        $data = RiceType::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        RiceType::find($id)->delete();
        return response()->json(
            [
                'success' => 'Rice Type deleted successfuly.'
            ]
        );
    }
}
