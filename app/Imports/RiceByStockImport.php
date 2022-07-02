<?php

namespace App\Imports;

use App\Models\RiceByStock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class RiceByStockImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new RiceByStock([
            'rice_type_id' => $row['rice_type_id'],
            'year' => $row['year'],
            'month' => $row['month'],
            'stock' => $row['stock'],
            'user_id' => Auth::user()->id
        ]);
    }
}
