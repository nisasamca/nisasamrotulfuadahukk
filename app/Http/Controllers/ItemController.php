<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.items', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required|exists:categories,id',
            'kondisi' => 'required',
            'lokasi' => 'required',
            'total_item' => 'required|integer|min:0',
            'jumlah_repair' => 'nullable|integer|min:0',
        ]);

        Item::create($request->all());
        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->all());
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id)
    {
        Item::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ItemsExport, 'data-barang.xlsx');
    }
}
