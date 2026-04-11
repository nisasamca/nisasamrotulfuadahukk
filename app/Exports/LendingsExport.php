<?php

namespace App\Exports;

use App\Models\Lending;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LendingsExport implements FromView
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $query = Lending::with('details');

        if ($this->startDate) {
            $query->whereDate('tanggal_pinjam', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('tanggal_pinjam', '<=', $this->endDate);
        }

        return view('exports.lendings', [
            'lendings' => $query->latest()->get()
        ]);
    }
}
