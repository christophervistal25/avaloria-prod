<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\DonatePaypal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SetupPaypalDonateController extends Controller
{
    public function index()
    {
        $donations = DonatePaypal::with('user_who_create')->get();
        return Inertia::render('Admin/SetupPaypalDonate', [
            'donations' => $donations,
        ]);
    }

    public function create()
    {
        return Inertia::render('Donations/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'equivalent_donate_points' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DonatePaypal::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'equivalent_donate_points' => $request->equivalent_donate_points,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Donation created successfully.']);
    }



    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'equivalent_donate_points' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $donate = DonatePaypal::find($id);

        $donate->name = $request->name;
        $donate->description = $request->description;
        $donate->is_active = $request->is_active;
        $donate->save();

        return response()->json(['message' => 'Donation updated successfully.']);
    }
}
