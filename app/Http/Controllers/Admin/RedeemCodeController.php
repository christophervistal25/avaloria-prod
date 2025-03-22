<?php

namespace App\Http\Controllers\Admin;

use App\Models\RedeemCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RedeemCodeController extends Controller
{
    public function index()
    {
        $redeemCodes = RedeemCode::with(['details', 'history', 'history.character:m_idPlayer,m_szName'])->latest()
                                ->where('status', '=', 'active')
                                ->paginate(10);
                                
        return inertia('Admin/RedeemCodes/Index', [
            'redeemCodes' => $redeemCodes
        ]);
    }

    public function create()
    {
        return inertia('Admin/RedeemCodes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required',
            'expires_at' => 'nullable|date',
            'details' => 'required|array|min:1',
            'details.*.item_name' => 'required',
            'details.*.item_description' => 'required',
            'details.*.item_quantity' => 'required|integer|min:1',
            'details.*.item_code' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        // generate 7 character secure random code
        $quantity = $request->input('quantity', 1); // Default to 1 if not specified

        for ($i = 0; $i < $quantity; $i++) {
            // Generate unique code
            do {
                $bytes = random_bytes(4);
                $validated['code'] = strtoupper(substr(bin2hex($bytes), 0, 7));
                // Check if code already exists
                $exists = RedeemCode::where('code', $validated['code'])->exists();
            } while ($exists);
            
            // Create record with the unique code
            DB::transaction(function () use ($validated) {
                $redeemCode = RedeemCode::create([
                    'code' => $validated['code'],
                    'description' => $validated['description'],
                    'expires_at' => $validated['expires_at'],
                    'status' => $validated['status'],
                ]);

                foreach ($validated['details'] as $detail) {
                    $redeemCode->details()->create($detail);
                }
            });
        }

      return redirect()->route('admin.redeem-codes.index')
                           ->with('success', 'Redeem code created successfully.');
    }
        
    public function edit(RedeemCode $redeemCode)
    {
        return inertia('Admin/RedeemCodes/Edit', [
            'redeemCode' => $redeemCode->load('details')
        ]);
    }

    public function update(Request $request, RedeemCode $redeemCode)
    {
        $validated = $request->validate([
            'code' => 'required|unique:redeem_codes,code,'.$redeemCode->id,
            'description' => 'required',
            'expires_at' => 'nullable|date',
            'status' => ['required', 'in:active,inactive'],
            'details' => 'required|array|min:1',
            'details.*.item_name' => 'required',
            'details.*.item_description' => 'required',
            'details.*.item_quantity' => 'required|integer|min:1',
            'details.*.item_code' => 'required'
        ]);


        return DB::transaction(function () use ($validated, $redeemCode) {
            $redeemCode->update([
                'code' => $validated['code'],
                'description' => $validated['description'],
                'expires_at' => $validated['expires_at'],
                'status' => $validated['status'],
            ]);

            // Delete existing details and create new ones
            $redeemCode->details()->delete();
            foreach ($validated['details'] as $detail) {
                $redeemCode->details()->create($detail);
            }

            return redirect()->route('admin.redeem-codes.index')
                           ->with('success', 'Redeem code updated successfully.');
        });
    }

     
        /**
         * Bulk delete redeem codes
         * 
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse
         */
        public function bulkDelete(Request $request)
        {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:redeem_codes,id'
            ]);
            

            
            DB::transaction(function () use ($validated) {
                // First delete related details to avoid foreign key constraints
                RedeemCode::whereIn('id', $validated['ids'])->each(function($code) {
                    $code->details()->delete();
                });
                
                // Then delete the codes themselves
                RedeemCode::whereIn('id', $validated['ids'])->delete();
            });
            
            return redirect()->route('admin.redeem-codes.index')
                        ->with('success', count($validated['ids']) . ' redeem codes deleted successfully.');
        }
}