<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\RedeemHistory;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class RedeemHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = RedeemHistory::query()
            ->with(['account', 'character', 'redeemCode.details']);

        // Apply search filter if a search query is provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            
           $query->where('code', 'like', '%'.$search.'%')->orWhere('account_name', 'like', '%'.$search.'%')
                    ->orWhere('character_id', 'like', '%'.$search.'%');
        }
        
        // Apply date range filter
        if ($request->has('date_range') && $request->date_range !== 'all') {
            $today = now()->startOfDay();
            
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', $today->copy()->subDay());
                    break;
                case 'week':
                    $query->whereDate('created_at', '>=', $today->copy()->subDays(7));
                    break;
                case 'month':
                    $query->whereDate('created_at', '>=', $today->copy()->subDays(30));
                    break;
            }
        }

        // Get paginated results
        $redeemHistories = $query->orderBy('created_at', 'desc')
                                ->paginate(15)
                                ->appends($request->query());

        // Get today's count for statistics
        $todayCount = RedeemHistory::whereDate('created_at', now()->toDateString())->count();
        
        return Inertia::render('Admin/RedeemHistory/Index', [
            'redeemHistories' => $redeemHistories,
            'todayCount' => $todayCount,
            'filters' => [
                'search' => $request->search ?? '',
                'date_range' => $request->date_range ?? 'all',
            ]
        ]);
    }
}