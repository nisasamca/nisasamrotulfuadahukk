<?php   
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lending;
use App\Models\LendingDetail;
use Illuminate\Support\Facades\Auth;

class LendingController extends Controller
{
    public function index()
    {
        
        $lendings = Lending::with('details')->latest()->get();
        return view('staff.lending', compact('lendings'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array',
            'total_items' => 'required|array',
            'ket' => 'required'
        ]);

        
        $lending = Lending::create([
            'name' => $request->name,
            'ket' => $request->ket,
            'edited_by' => Auth::user()->name, 
        ]);

       
        foreach ($request->items as $index => $item) {
            LendingDetail::create([
                'lending_id' => $lending->id,
                'item_name' => $item,
                'total' => $request->total_items[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Data peminjaman berhasil disimpan!');
    }

    public function return($id)
    {
        $lending = Lending::findOrFail($id);
        $lending->update(['is_returned' => true]);
        return redirect()->back()->with('success', 'Barang telah dikembalikan!');
    }

    public function destroy($id)
    {
        Lending::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}