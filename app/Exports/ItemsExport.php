<?php

namespace App\Exports;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ItemsExport implements FromView
{
    public function view(): View
    {
        return view('exports.items', [
            'items' => Item::all()
        ]);
    }
}
