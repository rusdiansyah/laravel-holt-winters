<?php

namespace App\Http\Controllers;

use App\Models\RiceByPrice;
use App\Models\RiceByStock;
use App\Models\RiceType;
use App\Models\Single;
use Illuminate\Http\Request;
use DataTables;
use DB;


class HoltWintersController extends Controller
{

    private $folder = 'holt_winters';

    public function index(Request $request)
    {
        // $this->Forecast();
        // $rice_type_id = 1;
        // $this->addPeramalan($rice_type_id);
        if ($request->ajax()) {
            $data = Single::whereNotNull('PE')
                ->with(['type']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        $list_type = RiceType::get();

        return view($this->folder . '.index', compact('list_type'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'rice_type_id' => 'required',
        ], [
            'rice_type_id.required' => 'Rice Type is required',
        ]);

        DB::table('single')->truncate();
        $riceByPrice = RiceByStock::where('rice_type_id', $request->rice_type_id)->get()->count();
        if ($riceByPrice > 1) {
            $this->addPeramalan($request->rice_type_id);
            return Response()->json(['success' => 'Generate Holt Winters Success..']);
        }
        return Response()->json(['error' => 'Rice Type ID Not Found.']);
    }

    public function addPeramalan($rice_type_id)
    {
        $riceByPrice = RiceByStock::where('rice_type_id', $rice_type_id)
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $price = $riceByPrice->groupBy(function ($item, $key) {
            return $item->month . ' ' . $item->year;
        });

        $riceByPrices = array();
        $i = 0;

        foreach ($price as $p) {
            $riceByPrices[$i] = $p->sum('stock');
            $i++;
        }
        // dd(count($riceByPrices));

        $forecast = array();
        $alfa = 0.1;
        $i = 0;
        $finalForecast = array();
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j <= count($riceByPrices); $j++) {
                if ($j == 0) {
                    $forecast[$j] = $riceByPrices[$j];
                } else {
                    $forecast[$j] = ($alfa * $riceByPrices[$j - 1] + (1 - $alfa) * $forecast[$j - 1]);
                }
                if ($j < count($riceByPrices)) {
                    $pe[$j] = abs(($forecast[$j] - $riceByPrices[$j]) / $riceByPrices[$j]);
                    $tempmse[$j] = ($forecast[$j] - $riceByPrices[$j]) * ($forecast[$j] - $riceByPrices[$j]);
                }
            }
            $mape = round(array_sum($pe) / count($riceByPrices), 6) * 100;
            $mse = round(array_sum($tempmse) / count($riceByPrices), 6);
            $rmse = round(sqrt($mse), 6);
            // dd($mse);
            // dd($forecast[count($riceByPrices)]);
            if ($alfa == 0.1) {
                $temp = $mape;
                $finalMape = $mape;
                $finalAlfa = $alfa;
                $finalForecast = $forecast;
                $finalPE = $pe;
                $finalMSE = $mse;
                $finalRMSE = $rmse;
                $hasil = $forecast[count($riceByPrices)];
            } else {
                // if ($mape > $temp) {
                //     // dd($mape);
                // } else {
                $hasil = $forecast[count($riceByPrices)];
                $temp = $mape;
                $finalMape = $temp;
                $finalAlfa = $alfa;
                $finalForecast = $forecast;
                $finalPE = $pe;
                $finalMSE = $mse;
                $finalRMSE = $rmse;
                // }
            }
            $alfa += 0.1;
            // dd($alfa);
            for ($a = 0; $a <= count($riceByPrices); $a++) {
                $ramal = new Single;
                if ($a == count($riceByPrices)) {
                    $ramal->nilai_aktual = 0;
                    $ramal->PE = NULL;
                } else {
                    $ramal->nilai_aktual = $riceByPrices[$a];
                    $ramal->PE = $finalPE[$a];
                }
                $ramal->rice_type_id = $rice_type_id;
                $ramal->ramal = $finalForecast[$a];
                $ramal->MAPE = $finalMape;
                $ramal->MSE = $finalMSE;
                $ramal->RMSE = $finalRMSE;
                $ramal->alfa = $finalAlfa;
                $ramal->save();
                $idSingle = $ramal->id;
            }
        }
    }


    public function Forecast()
    {

        $anYear1 = array();
        // $anYear1 = RiceByPrice::select('price')->where('year', 2021)->get()->toArray();
        // dd($anYear1);
        $anYear2 = array();
        // $anYear2 = RiceByPrice::select('price')->where('year', 2022)->get()->toArray();
        // dd($anYear2);

        $nStop = 10;
        for ($i = 1; $i <= 12; $i++) {
            // $anYear1[$i] = rand(100, 400);
            $anYear1[$i] = RiceByStock::select('stock')
                ->where('year', 2021)
                ->where('month', $i)
                ->first()
                ->stock;
            // dd($anYear1[$i]);
            if ($i <= $nStop) {
                // $anYear2[$i + 12] = rand(200, 600);
                $anYear2[$i + 12] = RiceByStock::select('stock')
                    ->where('year', 2022)
                    ->where('month', $i)
                    ->first()
                    ->stock;
            }
        }

        print_r($anYear1);
        print_r($anYear2);
        $anData = array_merge($anYear1, $anYear2);
        print_r($this->forecastHoltWinters($anData));
    }

    function forecastHoltWinters($anData, $nForecast = 2, $nSeasonLength = 4, $nAlpha = 0.2, $nBeta = 0.01, $nGamma = 0.01, $nDevGamma = 0.1)
    {

        // Calculate an initial trend level
        $nTrend1 = 0;
        for ($i = 0; $i < $nSeasonLength; $i++) {
            $nTrend1 += $anData[$i];
        }
        $nTrend1 /= $nSeasonLength;

        $nTrend2 = 0;
        for ($i = $nSeasonLength; $i < 2 * $nSeasonLength; $i++) {
            $nTrend2 += $anData[$i];
        }
        $nTrend2 /= $nSeasonLength;

        $nInitialTrend = ($nTrend2 - $nTrend1) / $nSeasonLength;

        // Take the first value as the initial level
        $nInitialLevel = $anData[0];

        // Build index
        $anIndex = array();
        foreach ($anData as $nKey => $nVal) {
            $anIndex[$nKey] = $nVal / ($nInitialLevel + ($nKey + 1) * $nInitialTrend);
        }

        // Build season buffer
        $anSeason = array_fill(0, count($anData), 0);
        for ($i = 0; $i < $nSeasonLength; $i++) {
            $anSeason[$i] = ($anIndex[$i] + $anIndex[$i + $nSeasonLength]) / 2;
        }

        // Normalise season
        $nSeasonFactor = $nSeasonLength / array_sum($anSeason);
        foreach ($anSeason as $nKey => $nVal) {
            $anSeason[$nKey] *= $nSeasonFactor;
        }

        $anHoltWinters = array();
        $anDeviations = array();
        $nAlphaLevel = $nInitialLevel;
        $nBetaTrend = $nInitialTrend;
        foreach ($anData as $nKey => $nVal) {
            $nTempLevel = $nAlphaLevel;
            $nTempTrend = $nBetaTrend;

            $nAlphaLevel = $nAlpha * $nVal / $anSeason[$nKey] + (1.0 - $nAlpha) * ($nTempLevel + $nTempTrend);
            $nBetaTrend = $nBeta * ($nAlphaLevel - $nTempLevel) + (1.0 - $nBeta) * $nTempTrend;

            $anSeason[$nKey + $nSeasonLength] = $nGamma * $nVal / $nAlphaLevel + (1.0 - $nGamma) * $anSeason[$nKey];

            $anHoltWinters[$nKey] = ($nAlphaLevel + $nBetaTrend * ($nKey + 1)) * $anSeason[$nKey];
            $anDeviations[$nKey] = $nDevGamma * abs($nVal - $anHoltWinters[$nKey]) + (1 - $nDevGamma)
                * (isset($anDeviations[$nKey - $nSeasonLength]) ? $anDeviations[$nKey - $nSeasonLength] : 0);
        }

        $anForecast = array();
        $nLast = end($anData);
        for ($i = 1; $i <= $nForecast; $i++) {
            $nComputed = round($nAlphaLevel + $nBetaTrend * $anSeason[$nKey + $i]);
            if ($nComputed < 0) { // wildly off due to outliers
                $nComputed = $nLast;
            }
            $anForecast[] = $nComputed;
        }

        return $anForecast;
    }
}
