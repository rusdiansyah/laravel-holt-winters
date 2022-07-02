<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\RiceByStockImport;
use Maatwebsite\Excel\Facades\Excel;

class RiceByStockImportController extends Controller
{
    private $folder = 'rice_by_stock_import';

    public function index()
    {
        return view($this->folder . '.index');
    }

    public function store(Request $request)
    {
        Excel::import(new RiceByStockImport, request()->file('file'));

        // dd($request->all());
        return redirect('rice_by_stock');
    }
}
