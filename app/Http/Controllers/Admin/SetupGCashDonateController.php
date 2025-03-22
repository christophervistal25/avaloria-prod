<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonateGCash;
use Illuminate\Http\Request;

class SetupGCashDonateController extends Controller
{
    public function index()
    {
        $donates = DonateGCash::with('user_who_create')->get();
        return inertia('Admin/SetupGCashDonate', [
            'donates' => $donates,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'mobile_number' => ['required', 'regex:/^09[0-9]{9}$/'],
            'amount' => 'required',
            'equivalent_donate_points' => 'required',
            'description' => ['required', 'max:255'],
            'is_active' => ['required'],
            'qr_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->qr_path->extension();

        $request->qr_path->move(storage_path('app/public/images'), $imageName);

        DonateGCash::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'amount' => $request->amount,
            'equivalent_donate_points' => $request->equivalent_donate_points,
            'description' => $request->description,
            'is_active' => $request->is_active,
            'qr_path' => $imageName,
            'created_by' => auth()->user()->id,
        ]);

        return response()->json(['success' => 'GCash Donate setup successfully.'], 201);
    }

    public function update(Request $request, $id)
    {
        $donate = DonateGCash::findOrFail($id);

        $request->validate([
            'name' => ['required'],
            'mobile_number' => ['required', 'regex:/^09[0-9]{9}$/'],
            'amount' => 'required',
            'equivalent_donate_points' => 'required',
            'description' => ['required', 'max:255'],
            'is_active' => ['required'],
            'qr_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('qr_path')) {
            $imageName = time().'.'.$request->qr_path->extension();
            $request->qr_path->move(storage_path('app/public/images'), $imageName);
            $donate->qr_path = $imageName;
        }

        $donate->update([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'description' => $request->description,
            'is_active' => $request->is_active,
            'qr_path' => $donate->qr_path,
        ]);

        return response()->json(['success' => 'GCash Donate updated successfully.'], 200);
    }


}
