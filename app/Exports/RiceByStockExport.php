<?php

namespace App\Exports;

use App\Models\RiceByStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RiceByStockExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return RiceByStock::all();
    }

    public function headings(): array
    {
        return ["rice_type_id", "year", "month", "stock"];
    }
}
