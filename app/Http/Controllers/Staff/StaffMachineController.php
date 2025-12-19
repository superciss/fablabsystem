<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\Supplier;

class StaffMachineController extends Controller
{
    public function index()
    {
        $machines = Machine::with('suppliers')->latest()->get();
        return view('staff.machine.index', compact('machines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'property_no' => 'nullable|string',
            'status'  => 'required|in:serviceable,non serviceable,return to supplier for repairing,functional',
            'cost' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'create_at.*' => 'nullable|date',
        ]);

        $data = $validated;

        Machine::create($data);

        return redirect()->route('staff.machine.index')->with('success', 'Machine Product added successfully!');
    }

    public function update(Request $request, Machine $id)
    {
        $validated = $request->validate([
             'machine_name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'property_no' => 'nullable|string',
            'status'  => 'required|in:serviceable,non serviceable,return to supplier for repairing,functional',
            'cost' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $data = $validated;

        $id->update($data);

        return redirect()->route('staff.machine.index')->with('success', 'Machine Product updated successfully!');
    }

    public function destroy(Machine $id)
    {
        $id->delete();
        return redirect()->route('staff.machine.index')->with('success', 'Machine Product deleted successfully!');
    }
}
