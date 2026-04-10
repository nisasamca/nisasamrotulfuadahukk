<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use App\Models\Item;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index() {
        $items = Item::all();
        return view('admin.items', compact('items'));
    }
    

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'kondisi' => 'required',
            'lokasi' => 'required',
        ]);

        Item::create($request->all());
        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);
        $item->update($request->all());
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id) {
        Item::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function export() 
    {
        return Excel::download(new ItemsExport, 'data-kategori.xlsx');
    }
}
