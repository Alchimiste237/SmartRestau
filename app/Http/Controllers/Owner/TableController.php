<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class TableController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        $tables = $restaurant->tables;

        return view('owner.tables.index', compact('tables', 'restaurant'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:50',
        ]);

        $restaurant = Auth::user()->restaurant;

        $table = RestaurantTable::create([
            'restaurant_id' => $restaurant->id,
            'table_number' => $request->table_number,
        ]);

        return back()->with('success', "Table {$table->table_number} created!");
    }

    public function destroy(RestaurantTable $table)
    {
        if ($table->restaurant_id !== Auth::user()->restaurant->id) {
            abort(403);
        }

        $table->delete();

        return back()->with('success', 'Table deleted!');
    }

    public function printPdf()
    {
        $restaurant = Auth::user()->restaurant;
        $tables = $restaurant->tables;

        $tableData = [];
        foreach ($tables as $table) {
            $url = $restaurant->getTableUrl($table->id);
            $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($url));
            
            $tableData[] = [
                'number' => $table->table_number,
                'qr_code' => $qrCode,
                'url' => $url
            ];
        }

        $pdf = Pdf::loadView('owner.tables.pdf', compact('restaurant', 'tableData'));
        
        return $pdf->download("{$restaurant->name}-qr-codes.pdf");
    }
}
