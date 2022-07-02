<?php

namespace App\Http\Controllers;

use App\Models\Single;
use Illuminate\Http\Request;

class ChartByPriceController extends Controller
{

    public function index()
    {
        $alfa = 0.9;
        $data = Single::where('alfa', 'like', $alfa)
            ->whereNotNull('PE')
            ->get();
        // dd($data);
        $result[] = ['Month', 'Price', 'Forecast'];
        $i = 1;
        foreach ($data as $key => $value) {
            $result[++$key] = [$this->getBulan($i++), (int)$value->nilai_aktual, (int)$value->ramal];
        }
        // dd($result);
        return view('chart_by_price.index')
            ->with('data', json_encode($result));
    }

    function  getBulan($bln)
    {
        switch ($bln) {
            case  1:
                return  "Januari";
                break;
            case  2:
                return  "Februari";
                break;
            case  3:
                return  "Maret";
                break;
            case  4:
                return  "April";
                break;
            case  5:
                return  "Mei";
                break;
            case  6:
                return  "Juni";
                break;
            case  7:
                return  "Juli";
                break;
            case  8:
                return  "Agustus";
                break;
            case  9:
                return  "September";
                break;
            case  10:
                return  "Oktober";
                break;
            case  11:
                return  "November";
                break;
            case  12:
                return  "Desember";
                break;
        }
    }
}
