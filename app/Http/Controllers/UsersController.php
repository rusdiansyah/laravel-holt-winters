<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    private $folder = 'users';

    public function index(Request $request)
    {
        // dd('hello rice type');

        if ($request->ajax()) {
            $data = User::select('*');
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

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = $request->validate([
            'email' => [
                'required',
                Rule::unique('users')->ignore($request->id)
            ],
            'name' => 'required',
        ], [
            'email.required' => 'Email is required',
            'email.unique' => 'Email is already exsist.',
            'name.required' => 'Name Max 100 Characters'
        ]);
        if ($request->password) {
            $validated['password'] = bcrypt($request->password);
        }
        // dd($validated);
        $data = User::updateOrCreate(
            [
                'id' => $id
            ],
            $validated
        );

        // return back()->with('success', 'Rice Type created successfully.');
        return Response()->json($data);
    }

    public function edit($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(
            [
                'success' => 'User Type deleted successfuly.'
            ]
        );
    }
}
