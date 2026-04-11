<?php   
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Lending;
use App\Models\LendingDetail;
use Illuminate\Support\Facades\Auth;

class LendingController extends Controller
{
    public function index(Request $request)
    {
        $query = Lending::with('details');

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
        }

        $lendings = $query->latest()->get();
        $items = Item::all();

        return view('staff.lending', compact('lendings', 'items'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array',
            'total_items' => 'required|array',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'ket' => 'required'
        ]);

        $requestedTotals = [];
        foreach ($request->items as $index => $itemName) {
            if (!isset($requestedTotals[$itemName])) {
                $requestedTotals[$itemName] = 0;
            }
            $requestedTotals[$itemName] += $request->total_items[$index];
        }

        foreach ($requestedTotals as $itemName => $requestedQty) {
            $itemRecord = Item::where('nama', $itemName)->first();
            if (!$itemRecord) {
                return redirect()->back()->with('error', "Barang $itemName tidak ditemukan!");
            }
            if ($requestedQty > $itemRecord->available) {
                return redirect()->back()->with('error', "Gagal! Stok '$itemName' tidak mencukupi (Tersedia: {$itemRecord->available}).");
            }
        }

        $lending = Lending::create([
            'name' => $request->name,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
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

    public function return(Request $request, $id)
    {
        $lending = Lending::with('details')->findOrFail($id);

        foreach ($lending->details as $detail) {
            $baik = $request->input("baik.{$detail->id}", 0);
            $rusak = $request->input("rusak.{$detail->id}", 0);
            
            $detail->update([
                'kondisi_kembali' => [
                    'baik' => (int)$baik,
                    'rusak' => (int)$rusak
                ]
            ]);

            if ($rusak > 0) {
                $item = \App\Models\Item::where('nama', $detail->item_name)->first();
                if ($item) {
                    $item->increment('jumlah_repair', $rusak);
                }
            }
        }

        $lending->update([
            'is_returned' => true,
            'tanggal_kembali' => $lending->tanggal_kembali ?? now()
        ]);

        return redirect()->back()->with('success', 'Barang berhasil dikembalikan!');
    }

    public function destroy($id)
    {
        Lending::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function exportPdf(Request $request, $id)
    {
        $lending = Lending::with('details')->findOrFail($id);
        $signature = $request->input('signature'); // base64 image dari canvas
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.lending_receipt', compact('lending', 'signature'));
        return $pdf->download('struk-peminjaman-' . $lending->id . '.pdf');
    }

    public function export(Request $request) 
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LendingsExport($request->start_date, $request->end_date), 
            'data-peminjaman.xlsx'
        );
    }
}
