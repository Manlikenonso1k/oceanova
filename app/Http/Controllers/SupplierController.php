<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function updateName(Request $request, $supplier): RedirectResponse
    {
        $request->validate([
            'new_supplier_name' => ['required', 'string', 'max:255'],
        ]);

        $oldName = trim($supplier);
        $newName = trim($request->input('new_supplier_name'));

        if ($oldName === $newName) {
            return redirect()->back()->with('info', 'No changes made.');
        }

        // Update all procurement records with the old supplier name
        DB::transaction(function () use ($oldName, $newName) {
            Procurement::whereRaw('LOWER(TRIM(supplier_name)) = ?', [mb_strtolower($oldName)])
                ->update(['supplier_name' => $newName]);
        });

        return redirect()->back()->with('success', 'Supplier name updated and merged successfully.');
    }
}
